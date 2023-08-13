<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->imageUrl(400, 400, 'products', true),
            'quantity' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->sentence(10),
        ];
    }
}
