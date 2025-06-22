<?php

namespace Tests\Feature\Api\Recipe;

use App\Models\Recipe;
use Database\Factories\KitchenFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexControllerTest extends RecipeTestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_recipes(): void
    {
        $this->assertDatabaseCount(Recipe::class, 0);
        $this->createRecipeWithRelations(2);
        $this->assertDatabaseCount(Recipe::class, 2);

        $response = $this->getJson(route('api.recipes.index'));
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'prep_time',
                        'cook_time',
                        'servings',
                        'img_url',
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
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(10, $response->json('meta.per_page'));
        $this->assertEquals(1, $response->json('meta.current_page'));
        $this->assertEquals(2, $response->json('meta.total'));
    }

    public function test_index_returns_empty_when_no_recipes(): void
    {
        $response = $this->getJson(route('api.recipes.index'));

        $response->assertOk()
            ->assertJson([
                'data' => [],
                'links' => [
                    'first' => route('api.recipes.index', ['page' => 1]),
                    'last' => route('api.recipes.index', ['page' => 1]),
                    'prev' => null,
                    'next' => null,
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => null,
                    'last_page' => 1,
                    'path' => route('api.recipes.index'),
                    'per_page' => 10,
                    'to' => null,
                    'total' => 0,
                ],
            ]);
    }

    public function test_index_filters_recipes_by_multiple_critrria(): void
    {
        $kitchen = KitchenFactory::new()->create(['id' => 1]);
        $kitchen2 = KitchenFactory::new()->create(['id' => 2]);

        $recipe1 = Recipe::factory()->create([
            'kitchen_id' => $kitchen->id,
            'title' => 'risotto',
        ]);

        Recipe::factory()->create(['kitchen_id' => $kitchen2->id, 'title' => 'Thit Kho']);
        Recipe::factory()->create(['servings' => 2]);
        Recipe::factory()->create(['prep_time' => 30]);
        Recipe::factory()->create(['title' => 'Chicken Dish']);

        $response = $this->getJson('/api/recipes?' . http_build_query([
            'kitchen' => 1,
            'servings' => 4,
            'max_prep_time' => 20,
            'max_cook_time' => 40,
            'title' => 'risotto',
        ]));

        $response = $this->getJson(route('api.recipes.index', ['kitchen' => $kitchen->id]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_kitchen(): void
    {
        $kitchen = KitchenFactory::new()->create(['id' => 1]);
        $kitchen2 = KitchenFactory::new()->create(['id' => 2]);

        $recipe1 = Recipe::factory()->create(['kitchen_id' => $kitchen->id]);
        Recipe::factory()->create(['kitchen_id' => $kitchen2->id]);

        $response = $this->getJson(route('api.recipes.index', ['kitchen' => $kitchen->id]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }
}
