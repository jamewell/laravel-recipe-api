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

    public function test_index_filters_recipes_by_multiple_criteria(): void
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

        $response = $this->getJson('/api/recipes?'.http_build_query([
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

    public function test_index_filters_recipes_by_servings(): void
    {
        $recipe1 = Recipe::factory()->create(['servings' => 4]);
        Recipe::factory()->create(['servings' => 2]);

        $response = $this->getJson(route('api.recipes.index', ['servings' => 4]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_min_servings(): void
    {
        $recipe1 = Recipe::factory()->create(['servings' => 4]);
        Recipe::factory()->create(['servings' => 2]);

        $response = $this->getJson(route('api.recipes.index', ['min_servings' => 3]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_max_servings(): void
    {
        $recipe1 = Recipe::factory()->create(['servings' => 4]);
        Recipe::factory()->create(['servings' => 6]);

        $response = $this->getJson(route('api.recipes.index', ['max_servings' => 5]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_prep_time(): void
    {
        $recipe1 = Recipe::factory()->create(['prep_time' => 30]);
        Recipe::factory()->create(['prep_time' => 20]);

        $response = $this->getJson(route('api.recipes.index', ['prep_time' => 30]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_max_prep_time(): void
    {
        $recipe1 = Recipe::factory()->create(['prep_time' => 20]);
        Recipe::factory()->create(['prep_time' => 30]);

        $response = $this->getJson(route('api.recipes.index', ['max_prep_time' => 25]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_cook_time(): void
    {
        $recipe1 = Recipe::factory()->create(['cook_time' => 30]);
        Recipe::factory()->create(['cook_time' => 20]);

        $response = $this->getJson(route('api.recipes.index', ['cook_time' => 30]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_max_cook_time(): void
    {
        $recipe1 = Recipe::factory()->create(['cook_time' => 20]);
        Recipe::factory()->create(['cook_time' => 40]);

        $response = $this->getJson(route('api.recipes.index', ['max_cook_time' => 25]));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_title(): void
    {
        $recipe1 = Recipe::factory()->create(['title' => 'risotto']);
        Recipe::factory()->create(['title' => 'Thit Kho']);

        $response = $this->getJson(route('api.recipes.index', ['title' => 'risotto']));
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_filters_recipes_by_title_with_empty_string(): void
    {
        $recipe1 = Recipe::factory()->create(['title' => 'risotto']);
        Recipe::factory()->create(['title' => 'Thit Kho']);

        $response = $this->getJson(route('api.recipes.index', ['title' => '']));
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $recipe1->id);
    }

    public function test_index_will_not_filter_by_non_existent_kitchen(): void
    {
        $kitchen = KitchenFactory::new()->create(['id' => 1]);
        Recipe::factory()->create(['kitchen_id' => $kitchen->id]);

        $response = $this->getJson(route('api.recipes.index', ['kitchen' => 999]));

        $response->assertUnprocessable()
            ->assertJson(['message' => 'The selected kitchen is invalid.'])
            ->assertJsonValidationErrors(['kitchen']);
    }
}
