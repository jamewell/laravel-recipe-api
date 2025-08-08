<?php

namespace Database\Seeders;

use App\Enums\UnitSystem;
use App\Enums\UnitType;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Seeder;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Volume (base unit: milliliter)
            ['full_name' => 'Milliliter', 'abbreviation' => 'ml', 'system' => UnitSystem::METRIC->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 1],
            ['full_name' => 'Liter', 'abbreviation' => 'l', 'system' => UnitSystem::METRIC->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 1000],

            // Imperial Volume
            ['full_name' => 'Teaspoon', 'abbreviation' => 'tsp', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 4.92892],
            ['full_name' => 'Tablespoon', 'abbreviation' => 'tbsp', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 14.7868],
            ['full_name' => 'Cup', 'abbreviation' => 'c', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 236.588],
            ['full_name' => 'Pint', 'abbreviation' => 'pt', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 473.176],
            ['full_name' => 'Quart', 'abbreviation' => 'qt', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 946.353],
            ['full_name' => 'Gallon', 'abbreviation' => 'gal', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 3785.41],
            ['full_name' => 'Fluid Ounce', 'abbreviation' => 'fl oz', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 29.5735],

            // Weight (base unit: gram)
            ['full_name' => 'Gram', 'abbreviation' => 'g', 'system' => UnitSystem::METRIC->value,
                'type' => UnitType::WEIGHT->value, 'base_equivalent' => 1],
            ['full_name' => 'Kilogram', 'abbreviation' => 'kg', 'system' => UnitSystem::METRIC->value,
                'type' => UnitType::WEIGHT->value, 'base_equivalent' => 1000],
            ['full_name' => 'Ounce', 'abbreviation' => 'oz', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::WEIGHT->value, 'base_equivalent' => 28.3495],
            ['full_name' => 'Pound', 'abbreviation' => 'lb', 'system' => UnitSystem::IMPERIAL->value,
                'type' => UnitType::WEIGHT->value, 'base_equivalent' => 453.592],

            // Small Measures
            ['full_name' => 'Dash', 'abbreviation' => 'dash', 'system' => UnitSystem::UNIVERSAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 0.616115],
            ['full_name' => 'Pinch', 'abbreviation' => 'pinch', 'system' => UnitSystem::UNIVERSAL->value,
                'type' => UnitType::VOLUME->value, 'base_equivalent' => 0.308058],

            // Count
            ['full_name' => 'Piece', 'abbreviation' => 'pc', 'system' => UnitSystem::UNIVERSAL->value,
                'type' => UnitType::COUNT->value, 'base_equivalent' => 1],
            ['full_name' => 'Dozen', 'abbreviation' => 'doz', 'system' => UnitSystem::UNIVERSAL->value,
                'type' => UnitType::COUNT->value, 'base_equivalent' => 12],
        ];

        foreach ($units as $unit) {
            UnitOfMeasurement::firstOrCreate(
                ['abbreviation' => $unit['abbreviation']],
                $unit
            );
        }
    }
}
