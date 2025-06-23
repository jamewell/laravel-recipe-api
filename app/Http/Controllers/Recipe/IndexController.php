<?php

namespace App\Http\Controllers\Recipe;

use App\Actions\Recipe\FilterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\IndexRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __construct(
        private readonly FilterAction $filterAction
    ) {}

    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        $query = Recipe::with([
            'user',
            'kitchen',
            'ingredients',
            'instructions',
        ]);

        $filteredRecipes = $this->filterAction->execute(
            $query,
            $filters
        );

        $recipes = $filteredRecipes->latest()->paginate(10);

        return RecipeResource::collection($recipes);
    }
}
