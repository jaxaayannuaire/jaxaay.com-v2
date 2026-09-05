<?php

namespace Tests\Feature;

use App\Enums\SubscriptionStatus;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PlansSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_plan_factory_prices_and_slug_constraint(): void
    {
        $plan = Plan::factory()->annualUnavailable()->create([
            'slug' => 'unique-plan',
            'price_monthly' => '0.00',
        ]);

        $this->assertSame('0.00', $plan->price_monthly);
        $this->assertNull($plan->price_yearly);
        $this->expectException(QueryException::class);
        Plan::factory()->create(['slug' => 'unique-plan']);
    }

    public function test_plan_factory_and_catalog_only_expose_active_plans_in_order(): void
    {
        $second = Plan::factory()->create(['name' => 'Zeta', 'slug' => 'zeta', 'sort_order' => 1]);
        $first = Plan::factory()->create(['name' => 'Beta', 'slug' => 'beta', 'sort_order' => 1]);
        Plan::factory()->inactive()->create(['name' => 'Hidden', 'slug' => 'hidden', 'sort_order' => 0]);

        $this->getJson('/api/v1/plans')
            ->assertOk()
            ->assertJsonPath('data.0.slug', $first->slug)
            ->assertJsonPath('data.1.slug', $second->slug)
            ->assertJsonMissing(['slug' => 'hidden'])
            ->assertJsonMissingPath('data.0.id');

        $this->getJson('/api/v1/plans/'.$first->slug)
            ->assertOk()
            ->assertJsonPath('data.slug', $first->slug)
            ->assertJsonMissingPath('data.id');
        $this->getJson('/api/v1/plans/hidden')->assertNotFound();
    }

    public function test_owner_can_create_monthly_subscription_with_snapshot(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $plan = Plan::factory()->create(['slug' => 'snapshot', 'price_monthly' => '10000.00', 'currency' => 'XOF']);

        $response = $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', [
                'plan' => $plan->slug,
                'billing_cycle' => 'monthly',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.billing_cycle', 'monthly')
            ->assertJsonPath('data.price', '10000.00')
            ->assertJsonPath('data.currency', 'XOF')
            ->assertJsonPath('data.plan.slug', $plan->slug)
            ->assertJsonMissingPath('data.id')
            ->assertJsonMissingPath('data.organization_id')
            ->assertJsonMissingPath('data.plan_id');

        $subscription = Subscription::query()->firstOrFail();
        $this->assertNull($subscription->starts_at);
        $this->assertNull($subscription->ends_at);
        $this->assertNull($subscription->trial_ends_at);
        $this->assertNull($subscription->grace_period_ends_at);
        $this->assertNull($subscription->cancelled_at);
        $plan->update(['price_monthly' => '12000.00']);
        $this->assertSame('10000.00', $subscription->fresh()->price);
        $this->assertSame('pending', $subscription->fresh()->status->value);
    }

    public function test_admin_can_create_yearly_subscription_and_member_cannot(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $organization->users()->attach($admin, ['role' => 'admin']);
        $organization->users()->attach($member, ['role' => 'member']);
        $plan = Plan::factory()->create(['slug' => 'annual', 'price_yearly' => '100000.00']);

        $this->asUserInOrganization($admin, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'yearly'])
            ->assertCreated()
            ->assertJsonPath('data.billing_cycle', 'yearly')
            ->assertJsonPath('data.price', '100000.00');

        $this->asUserInOrganization($member, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'yearly'])
            ->assertForbidden();
    }

    public function test_unavailable_yearly_and_inactive_plans_are_rejected(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $annualUnavailable = Plan::factory()->annualUnavailable()->create(['slug' => 'monthly-only']);
        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $annualUnavailable->slug, 'billing_cycle' => 'yearly'])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'BILLING_CYCLE_NOT_AVAILABLE');

        $inactive = Plan::factory()->inactive()->create(['slug' => 'inactive']);
        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $inactive->slug, 'billing_cycle' => 'monthly'])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'PLAN_INACTIVE');
    }

    public function test_only_one_current_subscription_is_allowed_but_terminal_history_can_be_replaced(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $plan = Plan::factory()->create(['slug' => 'core']);
        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'monthly'])
            ->assertCreated();

        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'monthly'])
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'SUBSCRIPTION_ALREADY_EXISTS');

        Subscription::query()->update(['status' => SubscriptionStatus::Cancelled]);
        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'monthly'])
            ->assertCreated();

        Subscription::query()->where('status', SubscriptionStatus::Pending->value)->update(['status' => SubscriptionStatus::Expired]);
        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'monthly'])
            ->assertCreated();
    }

    public function test_current_subscription_is_tenant_aware_and_members_can_view_it(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $member = User::factory()->create();
        $organization->users()->attach($member, ['role' => 'member']);
        $plan = Plan::factory()->create(['slug' => 'viewable']);

        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', ['plan' => $plan->slug, 'billing_cycle' => 'monthly'])
            ->assertCreated();
        $this->asUserInOrganization($member, $organization)
            ->getJson('/api/v1/organization/subscription')
            ->assertOk()
            ->assertJsonPath('data.plan.slug', $plan->slug);

        $otherUser = User::factory()->create();
        $otherOrganization = Organization::factory()->create(['owner_user_id' => $otherUser->id]);
        $otherOrganization->users()->attach($otherUser, ['role' => 'owner']);
        $this->asUserInOrganization($owner, $otherOrganization)
            ->getJson('/api/v1/organization/subscription')
            ->assertForbidden()
            ->assertJsonPath('error.code', 'ORGANIZATION_ACCESS_DENIED');
    }

    public function test_current_subscription_returns_not_found_without_a_non_terminal_subscription(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $plan = Plan::factory()->create();

        $this->asUserInOrganization($owner, $organization)
            ->getJson('/api/v1/organization/subscription')
            ->assertNotFound()
            ->assertJsonPath('error.code', 'SUBSCRIPTION_NOT_FOUND');

        Subscription::factory()->for($organization)->for($plan)->status(SubscriptionStatus::Cancelled)->create();
        $this->asUserInOrganization($owner, $organization)
            ->getJson('/api/v1/organization/subscription')
            ->assertNotFound();

        Subscription::query()->update(['status' => SubscriptionStatus::Expired]);
        $this->asUserInOrganization($owner, $organization)
            ->getJson('/api/v1/organization/subscription')
            ->assertNotFound();
    }

    public function test_payload_cannot_choose_another_tenant(): void
    {
        [$owner, $organization] = $this->organizationWithRole('owner');
        $otherUser = User::factory()->create();
        $otherOrganization = Organization::factory()->create(['owner_user_id' => $otherUser->id]);
        $otherOrganization->users()->attach($otherUser, ['role' => 'owner']);
        $plan = Plan::factory()->create(['slug' => 'tenant-safe']);

        $this->asUserInOrganization($owner, $organization)
            ->postJson('/api/v1/organization/subscription', [
                'plan' => $plan->slug,
                'billing_cycle' => 'monthly',
                'organization_id' => $otherOrganization->id,
            ])
            ->assertStatus(422);

        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_is_usable_obeys_status_and_dates(): void
    {
        Carbon::setTestNow('2026-09-05 12:00:00');
        $plan = Plan::factory()->create();

        $this->assertFalse(Subscription::factory()->for($plan)->status(SubscriptionStatus::Pending)->create()->isUsable());
        $this->assertFalse(Subscription::factory()->for($plan)->status(SubscriptionStatus::Cancelled)->create()->isUsable());
        $this->assertFalse(Subscription::factory()->for($plan)->status(SubscriptionStatus::Expired)->create()->isUsable());
        $this->assertTrue(Subscription::factory()->for($plan)->status(SubscriptionStatus::Trialing)->create(['trial_ends_at' => now()->addDay()])->isUsable());
        $this->assertFalse(Subscription::factory()->for($plan)->status(SubscriptionStatus::Trialing)->create(['trial_ends_at' => now()->subSecond()])->isUsable());
        $this->assertTrue(Subscription::factory()->for($plan)->status(SubscriptionStatus::Grace)->create(['grace_period_ends_at' => now()->addDay()])->isUsable());
        $this->assertFalse(Subscription::factory()->for($plan)->status(SubscriptionStatus::Grace)->create(['grace_period_ends_at' => now()->subSecond()])->isUsable());
        $this->assertTrue(Subscription::factory()->for($plan)->status(SubscriptionStatus::Active)->create(['ends_at' => now()->addDay()])->isUsable());
        $this->assertFalse(Subscription::factory()->for($plan)->status(SubscriptionStatus::Active)->create(['ends_at' => now()->subSecond()])->isUsable());
        $this->assertTrue(Subscription::factory()->for($plan)->status(SubscriptionStatus::Active)->create(['ends_at' => null])->isUsable());

        Carbon::setTestNow();
    }

    /** @return array{0: User, 1: Organization} */
    private function organizationWithRole(string $role): array
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create(['owner_user_id' => $user->id]);
        $organization->users()->attach($user, ['role' => $role]);

        return [$user, $organization];
    }

    private function asUserInOrganization(User $user, Organization $organization): self
    {
        Sanctum::actingAs($user);
        $this->withHeader('X-Organization-Id', $organization->public_id);

        return $this;
    }
}
