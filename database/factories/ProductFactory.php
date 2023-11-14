<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'product_type_id'=>1,
            'name' => fake()->name(),
            'slug' => fake()->name(),
            'old_price'=> fake()->numberBetween(15000,10000),
            'status'=>"enPreparation"

        ];
    }
}
