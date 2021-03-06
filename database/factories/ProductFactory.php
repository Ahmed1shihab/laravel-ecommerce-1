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
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'details' => $this->faker->sentence(8),
            'price' => $this->faker->numberBetween(1000, 50000),
            'description' => $this->faker->paragraph(),
            'featured' => false,
            'quantity' => 10
        ];
    }
}
