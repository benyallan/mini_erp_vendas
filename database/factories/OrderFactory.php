<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(5000, 20000);
        $shipping = $subtotal > 20000 ? 0 : ($subtotal >= 5200 && $subtotal <= 16659 ? 1500 : 2000);
        $total = $subtotal + $shipping;

        return [
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $total,
            'postal_code' => $this->faker->postcode,
            'address' => $this->faker->address,
        ];
    }
}
