<?php

namespace Database\Factories;

use App\Enums\Size;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Variation>
 */
class VariationFactory extends Factory
{
    protected $model = Variation::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(Size::cases())->value.' '.$this->faker->colorName,
            'price' => $this->faker->numberBetween(1500, 10000),
            'product_id' => Product::factory(),
        ];
    }
}
