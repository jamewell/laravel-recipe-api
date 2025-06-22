<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instruction>
 */
class InstructionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'step_number' => fake()->unique()->numberBetween(1, 100),
            'description' => fake()->sentence(),
            'img_url' => fake()->imageUrl(640, 480, 'food', true),
        ];
    }
}
