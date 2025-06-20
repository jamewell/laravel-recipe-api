<?php

namespace Tests\Feature\Api\Recipe;

use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Kitchen;
use App\Models\Recipe;
use App\Models\UnitOfMeasurement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RecipeTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Kitchen $kitchen;

    protected Ingredient $ingredient;

    protected UnitOfMeasurement $unitOfMeasurement;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->kitchen = Kitchen::factory()->create();
        $this->ingredient = Ingredient::factory()->create();
        $this->unitOfMeasurement = UnitOfMeasurement::factory()->create();

        Sanctum::actingAs($this->user, ['*']);
    }

    protected function createRecipeData(array $overrides = []): array
    {
        $recipeData = array_merge([
            'user_id' => $this->user->id,
            'kitchen_id' => $this->kitchen->id,
            'title' => 'Test Recipe',
            'description' => 'This is a test recipe.',
            'prep_time' => 30,
            'cook_time' => 45,
            'servings' => 4,
            'img_url' => null,
            'ingredients' => [
                [
                    'id' => $this->ingredient->id,
                    'unit_id' => $this->unitOfMeasurement->id,
                    'quantity' => 2,
                    'notes' => 'Test notes for ingredient',
                ],
            ],
            'instructions' => [
                ['description' => 'First step of the recipe'],
                ['description' => 'Second step of the recipe'],
            ],
        ], $overrides);

        return $recipeData;
    }

    protected function createRecipeWithRelations(int $count = 1): void
    {
        Recipe::factory()
            ->count($count)
            ->for($this->user)
            ->for($this->kitchen)
            ->hasAttached(
                $this->ingredient,
                [
                    'unit_id' => $this->unitOfMeasurement->id,
                    'quantity' => 2,
                    'notes' => 'Test notes for ingredient',
                ]
            )
            ->has(Instruction::factory()->count(2), 'instructions')
            ->create();
    }
}
