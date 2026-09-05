<?php

namespace App\Models;

use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): PlanFactory
    {
        return PlanFactory::new();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return $this->where($field ?: 'slug', $value)->first();
    }
}
