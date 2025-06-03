<?php

namespace Tests\Unit\Action\Recipe;

use App\Actions\Recipe\SyncIngredientsAction;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\UnitOfMeasurement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncIngredientsActionTest extends TestCase
{
    use RefreshDatabase;

    private SyncIngredientsAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new SyncIngredientsAction;
    }

    public function test_it_syncs_ingredients(): void
    {
        $recipe = Recipe::factory()->create();
        $ingredient1 = Ingredient::factory()->create(['id' => 1]);
        $ingredient2 = Ingredient::factory()->create(['id' => 2]);
        $unit = UnitOfMeasurement::factory()->create();

        $ingredients = [
            [
                'id' => $ingredient1->id,
                'quantity' => 100,
                'unit_id' => $unit->id,
                'notes' => 'Fresh if possible',
            ],
            [
                'id' => $ingredient2->id,
                'quantity' => 200,
                'unit_id' => $unit->id,
            ],
        ];

        $this->action->execute($recipe, $ingredients);

        $this->assertCount(2, $recipe->ingredients);
        $this->assertEquals(
            ['Fresh if possible', null],
            $recipe->ingredients->pluck('pivot.notes')->toArray()
        );
        $this->assertEquals(
            [100, 200],
            $recipe->ingredients->pluck('pivot.quantity')->toArray()
        );
    }

    public function test_it_removes_ingredients_not_in_sync_data()
    {
        $recipe = Recipe::factory()->create();
        $ingredient1 = Ingredient::factory()->create();
        $ingredient2 = Ingredient::factory()->create();
        $unit = UnitOfMeasurement::factory()->create();

        // First sync with both ingredients
        $this->action->execute($recipe, [
            ['id' => $ingredient1->id, 'quantity' => 100, 'unit_id' => $unit->id],
            ['id' => $ingredient2->id, 'quantity' => 200, 'unit_id' => $unit->id]
        ]);

        // Then sync with only one ingredient
        $this->action->execute($recipe, [
            ['id' => $ingredient1->id, 'quantity' => 150, 'unit_id' => $unit->id]
        ]);

        $this->assertCount(1, $recipe->fresh()->ingredients);
        $this->assertEquals($ingredient1->id, $recipe->ingredients->first()->id);
        $this->assertEquals(150, $recipe->ingredients->first()->pivot->quantity);
    }

    public function test_it_handles_empty_ingredients_array()
    {
        $recipe = Recipe::factory()->create();
        $ingredient = Ingredient::factory()->create();
        $unit = UnitOfMeasurement::factory()->create();

        // First add an ingredient
        $this->action->execute($recipe, [
            ['id' => $ingredient->id, 'quantity' => 100, 'unit_id' => $unit->id]
        ]);

        // Then sync with empty array
        $this->action->execute($recipe, []);

        $this->assertCount(0, $recipe->fresh()->ingredients);
    }
}
