<?php

namespace Tests\Feature\Api\Recipe;

use App\Models\Recipe;
use App\Models\User;

class DestroyControllerTest extends RecipeTestCase
{
    public function test_can_delete_recipe(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $response = $this->deleteJson(route('api.recipes.destroy', $recipe));

        $response->assertNoContent();
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_cannot_delete_recipe_if_not_owner(): void
    {
        $otherUser = User::factory()->create();
        $this->createRecipeWithRelations(1, $otherUser);
        $recipe = Recipe::first();

        $response = $this->deleteJson(route('api.recipes.destroy', $recipe));

        $response->assertUnauthorized()
            ->assertJson(['message' => 'You are not authorized to delete this recipe.']);
    }

    public function test_cannot_delete_non_existent_recipe(): void
    {
        $nonExistentRecipeId = 9999; // Assuming this ID does not exist

        $response = $this->deleteJson(route('api.recipes.destroy', $nonExistentRecipeId));

        $response->assertNotFound()
            ->assertJson(['message' => 'No query results for model [App\\Models\\Recipe] 9999']);
    }

    public function test_unauthenticated_user_cannot_delete_recipe(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $this->refreshApplication();

        $response = $this->deleteJson(route('api.recipes.destroy', $recipe));

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_can_delete_recipe_with_relations(): void
    {
        $this->createRecipeWithRelations();
        $recipe = Recipe::first();

        $response = $this->deleteJson(route('api.recipes.destroy', $recipe));

        $response->assertNoContent();
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
        $this->assertDatabaseMissing('recipe_ingredients', ['recipe_id' => $recipe->id]);
        $this->assertDatabaseMissing('instructions', ['recipe_id' => $recipe->id]);
    }
}
