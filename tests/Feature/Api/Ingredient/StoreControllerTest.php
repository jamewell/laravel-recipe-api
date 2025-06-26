<?php

namespace Tests\Feature\Api\Ingredient;

use App\Models\User;
use Database\Factories\IngredientCategoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_store_a_new_ingredient()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = IngredientCategoryFactory::new()->create([
            'name' => 'Vegetables',
            'description' => 'All kinds of vegetables',
        ]);

        $data = [
            'category_id' => $category->id,
            'name' => 'Tomato',
            'description' => 'Fresh tomatoes',
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'category',
                    'name',
                    'description',
                ],
            ]);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Tomato',
            'description' => 'Fresh tomatoes',
        ]);
    }

    public function test_it_requires_name_to_store_ingredient()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = IngredientCategoryFactory::new()->create([
            'name' => 'Vegetables',
            'description' => 'All kinds of vegetables',
        ]);

        $data = [
            'category_id' => $category->id,
            'description' => 'Fresh tomatoes',
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_valid_category_id_to_store_ingredient()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'category_id' => 999,
            'name' => 'Tomato',
            'description' => 'Fresh tomatoes',
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['category_id']);
    }

    public function test_it_requires_unique_name_to_store_ingredient()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = IngredientCategoryFactory::new()->create([
            'name' => 'Vegetables',
            'description' => 'All kinds of vegetables',
        ]);

        $existingIngredient = $category->ingredients()->create([
            'name' => 'Tomato',
            'description' => 'Fresh tomatoes',
        ]);

        $data = [
            'category_id' => $category->id,
            'name' => $existingIngredient->name,
            'description' => 'Another description',
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_valid_description_to_store_ingredient()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = IngredientCategoryFactory::new()->create([
            'name' => 'Vegetables',
            'description' => 'All kinds of vegetables',
        ]);

        $data = [
            'category_id' => $category->id,
            'name' => 'Tomato',
            'description' => 12345, // Invalid description
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['description']);
    }

    public function test_it_requires_category_id_to_store_ingredient()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Tomato',
            'description' => 'Fresh tomatoes',
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['category_id']);
    }

    public function test_unauthorized_user_cannot_store_ingredient()
    {
        $data = [
            'category_id' => 1,
            'name' => 'Tomato',
            'description' => 'Fresh tomatoes',
        ];

        $response = $this->postJson(route('api.ingredients.store'), $data);

        $response->assertUnauthorized();
    }
}
