<?php

namespace App\Http\Controllers\Recipe;

use App\Actions\Recipe\FilterAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __construct(
        private readonly FilterAction $filterAction
    ) {}

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $query = Recipe::with([
            'user',
            'kitchen',
            'ingredients',
            'instructions',
        ]);

        $filteredRecipes = $this->filterAction->execute(
            $query,
            $request->only([
                'kitchen',
                'servings',
                'min_servings',
                'max_servings',
                'prep_time',
                'max_prep_time',
                'cook_time',
                'max_cook_time',
                'title',
            ])
        );

        $recipes = $filteredRecipes->latest()->paginate(10);

        return RecipeResource::collection($recipes);
    }
}
