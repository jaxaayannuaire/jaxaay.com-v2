<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return OrganizationFactory::new();
    }

    protected $fillable = ['name', 'slug', 'status', 'owner_user_id'];

    protected $hidden = ['id', 'owner_user_id'];

    protected static function booted(): void
    {
        static::creating(function (self $organization): void {
            $organization->public_id ??= (string) Str::ulid();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->whereIn('status', SubscriptionStatus::currentValues())
            ->latest('id')
            ->first();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?: 'public_id', $value)->firstOrFail();
    }
}
