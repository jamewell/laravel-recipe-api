<?php

namespace Tests\Unit\Action\Recipe;

use App\Actions\Recipe\FilterAction as RecipeFilterAction;
use App\Models\Kitchen;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterActionTest extends TestCase
{
    use RefreshDatabase;

    private RecipeFilterAction $filterAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filterAction = new RecipeFilterAction;
    }

    public function test_it_filters_by_kitchen()
    {
        $kitchen1 = Kitchen::factory()->create(['id' => 1]);
        $kitchen2 = Kitchen::factory()->create(['id' => 2]);
        $recipe1 = Recipe::factory()->create(['kitchen_id' => $kitchen1->id]);
        $recipe2 = Recipe::factory()->create(['kitchen_id' => $kitchen2->id]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['kitchen' => $kitchen1->id]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
    }

    public function test_it_filters_by_servings()
    {
        $recipe1 = Recipe::factory()->create(['servings' => 4]);
        $recipe2 = Recipe::factory()->create(['servings' => 6]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['servings' => 4]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
    }

    public function test_it_filters_by_min_servings()
    {
        $recipe1 = Recipe::factory()->create(['servings' => 4]);
        $recipe2 = Recipe::factory()->create(['servings' => 6]);
        $recipe3 = Recipe::factory()->create(['servings' => 8]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['min_servings' => 5]
        );

        $this->assertCount(2, $query->get());
        $this->assertFalse($query->get()->contains($recipe1));
        $this->assertTrue($query->get()->contains($recipe2));
        $this->assertTrue($query->get()->contains($recipe3));
    }

    public function test_it_filters_by_max_servings()
    {
        $recipe1 = Recipe::factory()->create(['servings' => 4]);
        $recipe2 = Recipe::factory()->create(['servings' => 6]);
        $recipe3 = Recipe::factory()->create(['servings' => 8]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['max_servings' => 5]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
        $this->assertFalse($query->get()->contains($recipe3));
    }

    public function test_it_filters_by_prep_time()
    {
        $recipe1 = Recipe::factory()->create(['prep_time' => 10]);
        $recipe2 = Recipe::factory()->create(['prep_time' => 20]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['prep_time' => 10]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
    }

    public function test_it_filters_by_max_prep_time()
    {
        $recipe1 = Recipe::factory()->create(['prep_time' => 10]);
        $recipe2 = Recipe::factory()->create(['prep_time' => 20]);
        $recipe3 = Recipe::factory()->create(['prep_time' => 30]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['max_prep_time' => 15]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
        $this->assertFalse($query->get()->contains($recipe3));
    }

    public function test_it_filters_by_cook_time()
    {
        $recipe1 = Recipe::factory()->create(['cook_time' => 30]);
        $recipe2 = Recipe::factory()->create(['cook_time' => 45]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['cook_time' => 30]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
    }

    public function test_it_filters_by_max_cook_time()
    {
        $recipe1 = Recipe::factory()->create(['cook_time' => 30]);
        $recipe2 = Recipe::factory()->create(['cook_time' => 45]);
        $recipe3 = Recipe::factory()->create(['cook_time' => 60]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['max_cook_time' => 40]
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
        $this->assertFalse($query->get()->contains($recipe3));
    }

    public function test_it_filters_by_title()
    {
        $recipe1 = Recipe::factory()->create(['title' => 'Spaghetti Bolognese']);
        $recipe2 = Recipe::factory()->create(['title' => 'Chicken Curry']);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['title' => 'Spaghetti']
        );

        $this->assertCount(1, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertFalse($query->get()->contains($recipe2));
    }

    public function test_it_filters_by_empty_title()
    {
        $recipe1 = Recipe::factory()->create(['title' => 'Spaghetti Bolognese']);
        $recipe2 = Recipe::factory()->create(['title' => 'Chicken Curry']);

        $query = $this->filterAction->execute(
            Recipe::query(),
            ['title' => '']
        );

        $this->assertCount(2, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertTrue($query->get()->contains($recipe2));
    }

    public function test_it_combines_multiple_filters()
    {
        $kitchen = Kitchen::factory()->create(['id' => 1]);

        $recipe1 = Recipe::factory()->create([
            'kitchen_id' => $kitchen->id,
            'servings' => 4,
            'prep_time' => 10,
            'cook_time' => 30,
            'title' => 'Spaghetti Bolognese',
        ]);
        $recipe2 = Recipe::factory()->create([
            'kitchen_id' => $kitchen->id,
            'servings' => 6,
            'prep_time' => 20,
            'cook_time' => 45,
            'title' => 'Chicken Curry',
        ]);
        $recipe3 = Recipe::factory()->create([
            'kitchen_id' => $kitchen->id,
            'servings' => 4,
            'prep_time' => 10,
            'cook_time' => 30,
            'title' => 'Spaghetti Carbonara',
        ]);
        $recipe4 = Recipe::factory()->create([
            'kitchen_id' => $kitchen->id,
            'servings' => 2,
            'prep_time' => 5,
            'cook_time' => 15,
            'title' => 'Pasta Primavera',
        ]);

        $query = $this->filterAction->execute(
            Recipe::query(),
            [
                'kitchen' => $kitchen->id,
                'servings' => 4,
                'prep_time' => 10,
                'cook_time' => 30,
                'title' => 'Spaghetti',
            ]
        );

        $this->assertCount(2, $query->get());
        $this->assertTrue($query->get()->contains($recipe1));
        $this->assertTrue($query->get()->contains($recipe3));
        $this->assertFalse($query->get()->contains($recipe2));
        $this->assertFalse($query->get()->contains($recipe4));
    }
}
