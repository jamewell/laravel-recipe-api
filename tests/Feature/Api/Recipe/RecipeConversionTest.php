<?php

namespace Tests\Feature\Api\Recipe;

use App\Enums\UnitSystem;
use App\Models\Recipe;
use App\Models\UnitOfMeasurement;

class RecipeConversionTest extends RecipeTestCase
{
    public function test_it_includes_converted_units_when_preference_differs(): void
    {
        $metricUnit = UnitOfMeasurement::factory()->metricVolume()->create();
        $imperialUnit = UnitOfMeasurement::factory()->imperialVolume()->create();

        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $recipe->ingredients()->updateExistingPivot(
            $this->ingredient->id,
            [
                'unit_id' => $metricUnit->id,
                'quantity' => 250,
            ]
        );

        $this->user->measurementPreference()->create(['system' => UnitSystem::IMPERIAL]);

        $response = $this->getJson(route('api.recipes.show', $recipe->id));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'ingredients' => [
                        '*' => [
                            'unit' => [
                                'converted' => [
                                    'amount',
                                    'unit',
                                    'full_name',
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('data.ingredients.0.unit.converted.unit', 'fl oz');
    }

    public function test_it_does_not_include_conversion_when_preference_matches()
    {
        $metricUnit = UnitOfMeasurement::factory()->metricVolume()->create();

        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $recipe->ingredients()->updateExistingPivot(
            $this->ingredient->id,
            ['unit_id' => $metricUnit->id]
        );

        $this->user->measurementPreference()->create(['system' => UnitSystem::METRIC]);

        $response = $this->getJson(route('api.recipes.show', $recipe->id));

        $response->assertOk()
            ->assertJsonMissingPath('data.ingredients.0.unit.converted');
    }
}
