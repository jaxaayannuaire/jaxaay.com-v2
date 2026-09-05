<?php

namespace App\Services;

use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use App\Exceptions\SubscriptionException;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function create(Organization $organization, string $planSlug, BillingCycle $billingCycle): Subscription
    {
        return DB::transaction(function () use ($organization, $planSlug, $billingCycle): Subscription {
            $organization = Organization::query()->whereKey($organization->id)->lockForUpdate()->firstOrFail();

            if ($organization->subscriptions()->whereIn('status', SubscriptionStatus::currentValues())->exists()) {
                throw new SubscriptionException(
                    'SUBSCRIPTION_ALREADY_EXISTS',
                    'Une souscription courante existe déjà pour cette organisation.',
                    409,
                );
            }

            $plan = Plan::query()->where('slug', $planSlug)->first();
            if (! $plan) {
                throw new SubscriptionException('PLAN_NOT_FOUND', 'Plan introuvable.', 422);
            }
            if (! $plan->is_active) {
                throw new SubscriptionException('PLAN_INACTIVE', 'Le plan sélectionné est inactif.', 422);
            }

            $price = match ($billingCycle) {
                BillingCycle::Monthly => $plan->price_monthly,
                BillingCycle::Yearly => $plan->price_yearly,
            };

            if ($price === null) {
                throw new SubscriptionException(
                    'BILLING_CYCLE_NOT_AVAILABLE',
                    'Le cycle de facturation demandé n’est pas disponible pour ce plan.',
                    422,
                );
            }

            return $organization->subscriptions()->create([
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
                'price' => $price,
                'currency' => $plan->currency,
                'status' => SubscriptionStatus::Pending,
                'starts_at' => null,
                'ends_at' => null,
                'trial_ends_at' => null,
                'grace_period_ends_at' => null,
                'cancelled_at' => null,
            ])->load('plan');
        });
    }
}
