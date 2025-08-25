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
            'abbreviation' => fake()->unique()->lexify('???'),
            'description' => fake()->sentence(),
            'system' => UnitSystem::METRIC->value,
            'type' => UnitType::VOLUME->value,
            'base_equivalent' => fake()->randomFloat(4, 0.1, 1000),
        ];
    }

    public function metricVolume(): static
    {
        return $this->state([
            'system' => UnitSystem::METRIC,
            'type' => UnitType::VOLUME,
            'abbreviation' => 'ml',
            'full_name' => 'Milliliter',
            'base_equivalent' => 1,
        ]);
    }

    public function imperialVolume(): static
    {
        return $this->state([
            'system' => UnitSystem::IMPERIAL,
            'type' => UnitType::VOLUME,
            'abbreviation' => 'fl oz',
            'full_name' => 'Fluid Ounce',
            'base_equivalent' => 29.5735,
        ]);
    }
}
