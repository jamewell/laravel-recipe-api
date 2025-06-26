<?php

namespace Tests\Feature\Api\IngredientCategory;

use App\Models\IngredientCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_store_a_new_ingredient_category()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Vegetables',
            'description' => 'All kinds of vegetables',
        ];

        $response = $this->postJson(route('api.ingredient-categories.store'), $data);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                ],
            ]);

        $this->assertDatabaseHas('ingredient_categories', [
            'name' => 'Vegetables',
            'description' => 'All kinds of vegetables',
        ]);
    }

    public function test_it_requires_name_to_store_ingredient_category()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'description' => 'All kinds of vegetables',
        ];

        $response = $this->postJson(route('api.ingredient-categories.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_valid_description_to_store_ingredient_category()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Vegetables',
            'description' => 3,
        ];

        $response = $this->postJson(route('api.ingredient-categories.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['description']);
    }

    public function test_it_requires_unique_name_to_store_ingredient_category()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a category first
        IngredientCategory::factory()->create([
            'name' => 'Fruits',
        ]);

        $data = [
            'name' => 'Fruits', // Duplicate name
            'description' => 'All kinds of fruits',
        ];

        $response = $this->postJson(route('api.ingredient-categories.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_authenticated_user_to_store_ingredient_category()
    {
        $data = [
            'name' => 'Dairy',
            'description' => 'All kinds of dairy products',
        ];

        $response = $this->postJson(route('api.ingredient-categories.store'), $data);

        $response->assertUnauthorized();
    }
}
