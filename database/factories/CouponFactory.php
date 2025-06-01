<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'discount' => $this->faker->numberBetween(500, 5000),
            'minimum_amount' => $this->faker->numberBetween(10000, 30000),
            'expires_at' => $this->faker->dateTimeBetween('now', '+3 months'),
        ];
    }
}
