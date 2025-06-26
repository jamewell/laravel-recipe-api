<?php

namespace Tests\Feature\Api\Ingredient;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_ingredients(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $category = IngredientCategory::factory()->create(['name' => 'Fruits']);
        Ingredient::factory()->create([
            'category_id' => $category->id,
            'name' => 'Apple',
            'description' => 'A sweet red fruit',
        ]);
        $response = $this->getJson(route('api.ingredients.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'name',
                        'description',
                    ],
                ],
            ]);
    }

    public function test_it_can_filter_ingredients_by_category(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = IngredientCategory::factory()->create(['name' => 'Fruits']);
        $category2 = IngredientCategory::factory()->create(['name' => 'Vegetables']);
        Ingredient::factory()->create(['category_id' => $category->id, 'name' => 'Apple']);
        Ingredient::factory()->create(['category_id' => $category->id, 'name' => 'Banana']);
        Ingredient::factory()->create(['category_id' => $category2->id, 'name' => 'Carrot']);

        $response = $this->getJson(route('api.ingredients.index', ['category_id' => $category->id]));

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_it_can_paginate_ingredients(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ingredient::factory()->count(50)->create();

        $response = $this->getJson(route('api.ingredients.index', ['per_page' => 10]));

        $response->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'name',
                        'description',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_it_returns_empty_when_no_ingredients(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.ingredients.index'));

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_it_requires_authentication(): void
    {
        $response = $this->getJson(route('api.ingredients.index'));

        $response->assertUnauthorized();
    }

    public function test_it_requires_valid_category_id(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.ingredients.index', ['category_id' => 999]));

        $response->assertUnprocessable()
            ->assertJson([
                'message' => 'The selected category id is invalid.',
                'errors' => [
                    'category_id' => ['The selected category id is invalid.'],
                ],
            ]);
    }

    public function test_it_returns_ingredients_ordered_by_category_and_name(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category1 = IngredientCategory::factory()->create(['name' => 'Fruits']);
        $category2 = IngredientCategory::factory()->create(['name' => 'Vegetables']);

        Ingredient::factory()->create(['category_id' => $category1->id, 'name' => 'Banana']);
        Ingredient::factory()->create(['category_id' => $category1->id, 'name' => 'Apple']);
        Ingredient::factory()->create(['category_id' => $category2->id, 'name' => 'Carrot']);

        $response = $this->getJson(route('api.ingredients.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'name',
                        'description',
                    ],
                ],
            ])
            ->assertJsonFragment(['name' => 'Apple'])
            ->assertJsonFragment(['name' => 'Banana'])
            ->assertJsonFragment(['name' => 'Carrot']);
    }

    public function test_it_can_search_ingredients_by_name(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ingredient::factory()->create(['name' => 'Apple']);
        Ingredient::factory()->create(['name' => 'Banana']);
        Ingredient::factory()->create(['name' => 'Carrot']);

        $response = $this->getJson(route('api.ingredients.index', ['search' => 'Banana']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Banana']);
    }

    public function test_it_can_sort_ingredients_by_name(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ingredient::factory()->create(['name' => 'Date']);
        Ingredient::factory()->create(['name' => 'Banana']);
        Ingredient::factory()->create(['name' => 'Carrot']);
        Ingredient::factory()->create(['name' => 'Apple']);

        $response = $this->getJson(route('api.ingredients.index', ['sort' => 'name']));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'name',
                        'description',
                    ],
                ],
            ])
            ->assertJsonFragment(['name' => 'Apple'])
            ->assertJsonFragment(['name' => 'Banana'])
            ->assertJsonFragment(['name' => 'Carrot'])
            ->assertJsonFragment(['name' => 'Date']);
    }

    public function test_it_can_sort_ingredients_in_descending_order(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ingredient::factory()->create(['name' => 'Date']);
        Ingredient::factory()->create(['name' => 'Banana']);
        Ingredient::factory()->create(['name' => 'Carrot']);
        Ingredient::factory()->create(['name' => 'Apple']);

        $response = $this->getJson(route('api.ingredients.index', ['sort' => 'name', 'sort_direction' => 'desc']));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'name',
                        'description',
                    ],
                ],
            ])
            ->assertJsonFragment(['name' => 'Date'])
            ->assertJsonFragment(['name' => 'Carrot'])
            ->assertJsonFragment(['name' => 'Banana'])
            ->assertJsonFragment(['name' => 'Apple']);
    }

    public function test_search_is_case_insensitive(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ingredient::factory()->create(['name' => 'Apple']);
        Ingredient::factory()->create(['name' => 'banana']);
        Ingredient::factory()->create(['name' => 'Carrot']);

        $response = $this->getJson(route('api.ingredients.index', ['search' => 'BANANA']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'banana']);
    }

    public function test_it_can_handle_empty_search(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ingredient::factory()->create(['name' => 'Apple']);
        Ingredient::factory()->create(['name' => 'Banana']);
        Ingredient::factory()->create(['name' => 'Carrot']);

        $response = $this->getJson(route('api.ingredients.index', ['search' => '']));

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }
}
