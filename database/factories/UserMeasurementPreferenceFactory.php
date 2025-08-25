<?php

namespace Database\Factories;

use App\Enums\UnitSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserMeasurementPreference>
 */
class UserMeasurementPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new()->create()->id,
            'system' => UnitSystem::METRIC->value,
        ];
    }
}
