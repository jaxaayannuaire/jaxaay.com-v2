<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CoreSaaSTest extends TestCase
{
    use RefreshDatabase;

    public function test_testing_app_key_is_available(): void
    {
        $this->assertNotEmpty(config('app.key'));
    }

    public function test_register_login_me_and_logout(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Alice', 'email' => 'alice@example.com',
            'password' => 'password-123', 'password_confirmation' => 'password-123',
        ]);
        $response->assertCreated()->assertJsonMissingPath('data.password');
        $token = $response->json('token');
        $currentToken = PersonalAccessToken::findToken($token);
        $this->assertNotNull($currentToken);

        $otherToken = User::where('email', 'alice@example.com')->firstOrFail()->createToken('secondary');

        $this->withToken($token)->getJson('/api/v1/auth/me')->assertOk()->assertJsonMissingPath('data.password');
        $this->withToken($token)->postJson('/api/v1/auth/logout')->assertOk();
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $currentToken->getKey()]);
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $otherToken->accessToken->getKey()]);

        app('auth')->forgetGuards();

        $this->withToken($token)->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_organization_creation_owner_membership_and_slug_collision(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $first = $this->postJson('/api/v1/organizations', ['name' => 'Jaxaay Group'])->assertCreated();
        $second = $this->postJson('/api/v1/organizations', ['name' => 'Jaxaay Group'])->assertCreated();
        $this->assertNotSame($first->json('data.slug'), $second->json('data.slug'));
        $this->assertSame('owner', $user->organizations()->first()->pivot->role);
        $this->assertMatchesRegularExpression('/^[0-9A-HJKMNP-TV-Z]{26}$/', $first->json('data.public_id'));
    }

    public function test_tenant_context_is_explicit_and_isolated(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $orgA = Organization::factory()->create(['owner_user_id' => $a->id]);
        $orgA->users()->attach($a, ['role' => 'owner']);
        $orgB = Organization::factory()->create(['owner_user_id' => $b->id]);
        $orgB->users()->attach($b, ['role' => 'owner']);
        Sanctum::actingAs($a);
        $this->getJson('/api/v1/organization/context')->assertStatus(400)->assertJsonPath('error.code', 'ORGANIZATION_CONTEXT_REQUIRED');
        $this->withHeader('X-Organization-Id', $orgB->public_id)->getJson('/api/v1/organization/context')->assertStatus(403)->assertJsonPath('error.code', 'ORGANIZATION_ACCESS_DENIED');
        $this->withHeader('X-Organization-Id', $orgA->public_id)->getJson('/api/v1/organization/context')
            ->assertOk()
            ->assertJsonPath('data.public_id', $orgA->public_id)
            ->assertJsonMissing(['data' => ['public_id' => $orgB->public_id]]);
        $orgA->update(['status' => 'suspended']);
        $this->withHeader('X-Organization-Id', $orgA->public_id)->getJson('/api/v1/organization/context')->assertStatus(403)->assertJsonPath('error.code', 'ORGANIZATION_SUSPENDED');

        Sanctum::actingAs($b);
        $this->withHeader('X-Organization-Id', $orgB->public_id)->getJson('/api/v1/organization/context')
            ->assertOk()
            ->assertJsonPath('data.public_id', $orgB->public_id)
            ->assertJsonMissing(['data' => ['public_id' => $orgA->public_id]]);
    }
}
