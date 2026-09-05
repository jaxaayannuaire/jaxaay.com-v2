<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->organizations()->whereKey($subscription->organization_id)->exists();
    }

    public function create(User $user, Organization $organization): bool
    {
        return $organization->users()
            ->whereKey($user->id)
            ->wherePivotIn('role', ['owner', 'admin'])
            ->exists();
    }
}
