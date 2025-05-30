<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'stock' => $this->faker->numberBetween(1, 100),
            'category' => $this->faker->randomElement(['camiseta', 'bolsa', 'pantalón', 'chaqueta']),
            'description' => $this->faker->sentence(),
            'URL01' => $this->faker->imageUrl(),
            'URL02' => $this->faker->imageUrl(),
            'URL03' => $this->faker->imageUrl(),
            'URL04' => $this->faker->imageUrl(),
            // añade aquí los campos que tenga tu tabla
        ];
    }
}
