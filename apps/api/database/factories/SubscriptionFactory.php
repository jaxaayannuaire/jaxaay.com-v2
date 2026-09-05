<?php

namespace Database\Factories;

use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Subscription> */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $plan = Plan::factory();

        return [
            'organization_id' => Organization::factory(),
            'plan_id' => $plan,
            'billing_cycle' => BillingCycle::Monthly,
            'price' => '10000.00',
            'currency' => 'XOF',
            'status' => SubscriptionStatus::Pending,
            'starts_at' => null,
            'ends_at' => null,
            'trial_ends_at' => null,
            'grace_period_ends_at' => null,
            'cancelled_at' => null,
        ];
    }

    public function status(SubscriptionStatus $status): static
    {
        return $this->state(fn (): array => ['status' => $status]);
    }
}
