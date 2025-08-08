<?php

namespace Database\Factories;

use App\Enums\UnitSystem;
use App\Enums\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitOfMeasurement>
 */
class UnitOfMeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->unique()->word(),
            'abbreviation' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'system' => UnitSystem::METRIC->value,
            'type' => UnitType::VOLUME->value,
            'base_equivalent' => fake()->numberBetween(1, 10),
        ];
    }
}
