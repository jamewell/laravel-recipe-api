<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kitchenId = KitchenFactory::new()->create()->id;

        return [
            'user_id' => User::factory(),
            'kitchen_id' => $kitchenId,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'prep_time' => fake()->numberBetween(0, 120),
            'cook_time' => fake()->numberBetween(0, 120),
            'servings' => fake()->numberBetween(1, 10),
            'img_url' => fake()->imageUrl(640, 480, 'food', true),
        ];
    }
}
