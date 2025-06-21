<?php

namespace Tests\Feature\Api\Recipe;

use App\Models\Recipe;

class ShowControllerTest extends RecipeTestCase
{
    public function test_it_can_show_a_recipe(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $response = $this->getJson(route('api.recipes.show', $recipe->id));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'img_url',
                    'prep_time',
                    'cook_time',
                    'servings',
                    'kitchen' => [
                        'name',
                        'description',
                    ],
                    'ingredients' => [
                        '*' => [
                            'id',
                            'name',
                            'quantity',
                            'unit' => [
                                'full_name',
                                'abbreviation',
                                'description',
                            ],
                            'notes',
                        ],
                    ],
                    'instructions' => [
                        '*' => [
                            'description',
                            'img_url',
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('data.id', $recipe->id);
    }

    public function test_it_returns_404_for_non_existent_recipe(): void
    {
        $response = $this->getJson(route('api.recipes.show', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_404_for_invalid_recipe_id(): void
    {
        $response = $this->getJson(route('api.recipes.show', 'invalid-id'));

        $response->assertNotFound();
    }

    public function test_it_returns_404_for_deleted_recipe(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();
        $recipe->delete();

        $response = $this->getJson(route('api.recipes.show', $recipe->id));

        $response->assertNotFound();
    }
}
