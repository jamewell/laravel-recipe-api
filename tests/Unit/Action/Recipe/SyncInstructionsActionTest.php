<?php

namespace Tests\Unit\Action\Recipe;

use App\Actions\Recipe\SyncInstructionsAction;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncInstructionsActionTest extends TestCase
{
    use RefreshDatabase;

    private SyncInstructionsAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new SyncInstructionsAction;
    }

    public function test_it_syncs_instructions(): void
    {
        $recipe = Recipe::factory()->create();

        $instructions = [
            ['description' => 'Preheat the oven to 180°C'],
            ['description' => 'Mix the ingredients together'],
            ['description' => 'Bake for 30 minutes'],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(3, $recipe->instructions);
        $this->assertEquals(
            ['Preheat the oven to 180°C', 'Mix the ingredients together', 'Bake for 30 minutes'],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2, 3],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }

    public function test_it_removes_existing_instructions(): void
    {
        $recipe = Recipe::factory()->create();
        $recipe->instructions()->createMany([
            ['description' => 'Old instruction 1', 'step_number' => 1],
            ['description' => 'Old instruction 2', 'step_number' => 2],
        ]);

        $instructions = [
            ['description' => 'New instruction 1'],
            ['description' => 'New instruction 2'],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(2, $recipe->instructions);
        $this->assertEquals(
            ['New instruction 1', 'New instruction 2'],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }

    public function test_it_handles_empty_instructions(): void
    {
        $recipe = Recipe::factory()->create();

        $this->action->execute($recipe, []);

        $this->assertCount(0, $recipe->instructions);
    }

    public function test_it_handles_instructions_with_special_characters(): void
    {
        $recipe = Recipe::factory()->create();

        $instructions = [
            ['description' => 'First step: Preheat the oven!'],
            ['description' => 'Second step: Mix ingredients & bake.'],
            ['description' => 'Third step: Enjoy your meal!'],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(3, $recipe->instructions);
        $this->assertEquals(
            [
                'First step: Preheat the oven!',
                'Second step: Mix ingredients & bake.',
                'Third step: Enjoy your meal!',
            ],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2, 3],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }

    public function test_it_handles_instructions_with_html_tags(): void
    {
        $recipe = Recipe::factory()->create();

        $instructions = [
            ['description' => '<strong>First step:</strong> Preheat the oven to 180°C'],
            ['description' => '<em>Second step:</em> Mix the ingredients together'],
            ['description' => 'Third step: Bake for 30 minutes'],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(3, $recipe->instructions);
        $this->assertEquals(
            [
                '<strong>First step:</strong> Preheat the oven to 180°C',
                '<em>Second step:</em> Mix the ingredients together',
                'Third step: Bake for 30 minutes',
            ],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2, 3],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }

    public function test_it_handles_instructions_with_unicode_characters(): void
    {
        $recipe = Recipe::factory()->create();

        $instructions = [
            ['description' => 'First step: Préheat the oven to 180°C'],
            ['description' => 'Second step: Mix the ingrédients together'],
            ['description' => 'Third step: Bake for 30 minutes'],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(3, $recipe->instructions);
        $this->assertEquals(
            [
                'First step: Préheat the oven to 180°C',
                'Second step: Mix the ingrédients together',
                'Third step: Bake for 30 minutes',
            ],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2, 3],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }

    public function test_it_handles_instructions_with_long_descriptions(): void
    {
        $recipe = Recipe::factory()->create();

        $instructions = [
            ['description' => str_repeat('A', 255)], // Max length for a string in MySQL
            ['description' => str_repeat('B', 255)],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(2, $recipe->instructions);
        $this->assertEquals(
            [str_repeat('A', 255), str_repeat('B', 255)],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }

    public function test_it_handles_instructions_with_special_characters_in_step_numbers(): void
    {
        $recipe = Recipe::factory()->create();

        $instructions = [
            ['description' => 'Step 1: Preheat the oven'],
            ['description' => 'Step 2: Mix ingredients'],
            ['description' => 'Step 3: Bake for 30 minutes'],
        ];

        $this->action->execute($recipe, $instructions);

        $this->assertCount(3, $recipe->instructions);
        $this->assertEquals(
            ['Step 1: Preheat the oven', 'Step 2: Mix ingredients', 'Step 3: Bake for 30 minutes'],
            $recipe->instructions->pluck('description')->toArray()
        );
        $this->assertEquals(
            [1, 2, 3],
            $recipe->instructions->pluck('step_number')->toArray()
        );
    }
}
