<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'public_id' => $this->public_id,
            'status' => $this->status?->value,
            'billing_cycle' => $this->billing_cycle?->value,
            'price' => $this->price,
            'currency' => $this->currency,
            'starts_at' => $this->starts_at?->toISOString(),
            'ends_at' => $this->ends_at?->toISOString(),
            'trial_ends_at' => $this->trial_ends_at?->toISOString(),
            'grace_period_ends_at' => $this->grace_period_ends_at?->toISOString(),
            'cancelled_at' => $this->cancelled_at?->toISOString(),
            'plan' => [
                'slug' => $this->plan?->slug,
                'name' => $this->plan?->name,
            ],
        ];
    }
}
