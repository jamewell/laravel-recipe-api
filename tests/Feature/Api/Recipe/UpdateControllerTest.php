<?php

namespace Tests\Feature\Api\Recipe;

use App\Models\Recipe;
use App\Models\User;

class UpdateControllerTest extends RecipeTestCase
{
    public function test_it_can_update_a_recipe(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'title' => 'Updated Recipe Title',
            'description' => 'Updated description for the recipe.',
            'ingredients' => [
                [
                    'id' => $this->ingredient->id,
                    'unit_id' => $this->unitOfMeasurement->id,
                    'quantity' => 3,
                    'notes' => 'Updated notes for ingredient',
                ],
            ],
            'instructions' => [
                ['description' => 'Updated first step of the recipe'],
                ['description' => 'Updated second step of the recipe'],
            ],
            'img_url' => 'https://example.com/updated-image.jpg',
            'prep_time' => 25,
            'cook_time' => 35,
            'servings' => 6,
            'kitchen_id' => $this->kitchen->id,
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

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
            ->assertJsonPath('data.id', $recipe->id)
            ->assertJsonPath('data.title', $updateData['title'])
            ->assertJsonPath('data.description', $updateData['description'])
            ->assertJsonPath('data.ingredients.0.notes', $updateData['ingredients'][0]['notes'])
            ->assertJsonPath('data.instructions.0.description', $updateData['instructions'][0]['description']);
    }

    public function test_it_requires_authentication_to_update_recipe(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $this->refreshApplication();

        $updateData = $this->createRecipeData([
            'title' => 'Updated Recipe Title',
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertUnauthorized();
    }

    public function test_it_forbids_unauthorized_user_from_updating_recipe(): void
    {
        $otherUser = User::factory()->create();

        $this->createRecipeWithRelations(1, $otherUser);
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'title' => 'Unauthorized Update Attempt',
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertForbidden();
    }

    public function test_it_validates_required_fields_on_update(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'title' => '', // Empty title
            'ingredients' => [
                [
                    'id' => $this->ingredient->id,
                    'unit_id' => $this->unitOfMeasurement->id,
                    'quantity' => -2, // Invalid quantity
                ],
            ],
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'ingredients.0.quantity']);
    }

    public function test_it_validates_existing_kitchen_on_update(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'kitchen_id' => 999, // Non-existent kitchen ID
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['kitchen_id']);
    }

    public function test_it_validates_ingredients_on_update(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'ingredients' => [
                [
                    'id' => 999, // Non-existent ingredient ID
                    'unit_id' => $this->unitOfMeasurement->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ingredients.0.id']);
    }

    public function test_it_validates_instructions_on_update(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'instructions' => [
                ['description' => ''], // Empty instruction description
            ],
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['instructions.0.description']);
    }

    public function test_it_returns_404_for_non_existent_recipe_on_update(): void
    {
        $updateData = $this->createRecipeData([
            'title' => 'Non-existent Recipe Update',
        ]);

        $response = $this->putJson(route('api.recipes.update', 999), $updateData);

        $response->assertNotFound();
    }

    public function test_it_returns_404_for_invalid_recipe_id_on_update(): void
    {
        $updateData = $this->createRecipeData([
            'title' => 'Invalid Recipe ID Update',
        ]);

        $response = $this->putJson(route('api.recipes.update', 'invalid-id'), $updateData);

        $response->assertNotFound();
    }

    public function test_it_updates_recipe_with_optional_fields(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $updateData = $this->createRecipeData([
            'img_url' => 'https://example.com/optional-image.jpg',
            'prep_time' => 20,
            'cook_time' => 30,
            'servings' => 5,
        ]);

        $response = $this->putJson(route('api.recipes.update', $recipe->id), $updateData);

        $response->assertOk()
            ->assertJsonPath('data.img_url', $updateData['img_url'])
            ->assertJsonPath('data.prep_time', $updateData['prep_time'])
            ->assertJsonPath('data.cook_time', $updateData['cook_time'])
            ->assertJsonPath('data.servings', $updateData['servings']);
    }
}
