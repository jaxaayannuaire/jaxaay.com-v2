<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'organization_id',
        'plan_id',
        'billing_cycle',
        'price',
        'currency',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'grace_period_ends_at',
        'cancelled_at',
    ];

    protected $hidden = ['id', 'organization_id', 'plan_id'];

    protected function casts(): array
    {
        return [
            'billing_cycle' => BillingCycle::class,
            'status' => SubscriptionStatus::class,
            'price' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'grace_period_ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function newFactory(): SubscriptionFactory
    {
        return SubscriptionFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (self $subscription): void {
            $subscription->public_id ??= (string) Str::ulid();
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isCurrent(): bool
    {
        return in_array($this->status?->value, SubscriptionStatus::currentValues(), true);
    }

    public function isUsable(?Carbon $now = null): bool
    {
        $now ??= now();

        return match ($this->status) {
            SubscriptionStatus::Pending,
            SubscriptionStatus::Cancelled,
            SubscriptionStatus::Expired => false,
            SubscriptionStatus::Trialing => $this->trial_ends_at?->isFuture() ?? false,
            SubscriptionStatus::Grace => $this->grace_period_ends_at?->isFuture() ?? false,
            SubscriptionStatus::Active => (! $this->starts_at || $this->starts_at->lessThanOrEqualTo($now))
                && (! $this->ends_at || $this->ends_at->greaterThan($now)),
            default => false,
        };
    }
}
