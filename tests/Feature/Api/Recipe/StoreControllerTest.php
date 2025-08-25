<?php

namespace Tests\Feature\Api\Recipe;

use App\Models\Recipe;

class StoreControllerTest extends RecipeTestCase
{
    public function test_it_creates_a_recipe_with_valid_data()
    {
        $recipeData = $this->createRecipeData();

        $response = $this->postJson(route('api.recipes.store'), $recipeData);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'prep_time',
                    'cook_time',
                    'servings',
                    'img_url',
                    'user' => [
                        'id',
                        'username',
                    ],
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
                                'id',
                                'full_name',
                                'abbreviation',
                                'system',
                                'type',
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
            ]);

        $this->assertDatabaseHas('recipes', [
            'title' => 'Test Recipe',
            'user_id' => $this->user->id,
            'kitchen_id' => $this->kitchen->id,
        ]);

        $recipe = Recipe::first();
        $this->assertCount(1, $recipe->ingredients);
        $this->assertCount(2, $recipe->instructions);
    }

    public function test_it_requires_authentication()
    {
        $this->refreshApplication();

        $response = $this->postJson(route('api.recipes.store'), $this->createRecipeData());

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_it_validates_required_fields()
    {
        $response = $this->postJson(route('api.recipes.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'kitchen_id',
                'title',
                'ingredients',
                'instructions',
            ]);
    }

    public function test_it_validates_ingredients_and_instructions()
    {
        $recipeData = $this->createRecipeData();
        unset($recipeData['ingredients'], $recipeData['instructions']);

        $response = $this->postJson(route('api.recipes.store'), $recipeData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'ingredients',
                'instructions',
            ]);

        // Test with invalid ingredient data
        $recipeData['ingredients'] = [
            ['id' => 999, 'quantity' => 0, 'unit_id' => null, 'notes' => ''],
        ];

        $response = $this->postJson(route('api.recipes.store'), $recipeData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'ingredients.0.id',
                'ingredients.0.quantity',
                'ingredients.0.unit_id',
            ]);
    }
}
