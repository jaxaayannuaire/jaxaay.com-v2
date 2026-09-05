<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Plan> */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'price_monthly' => '10000.00',
            'price_yearly' => '100000.00',
            'currency' => 'XOF',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }

    public function annualUnavailable(): static
    {
        return $this->state(fn (): array => ['price_yearly' => null]);
    }
}
