<?php

namespace Database\Factories;

use App\Models\IngredientCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryId = IngredientCategoryFactory::new()->create()->id;

        return [
            'id' => fake()->unique()->randomNumber(5),
            'category_id' => $categoryId,
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
