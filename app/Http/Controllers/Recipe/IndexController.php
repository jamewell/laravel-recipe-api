<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        $recipes = Recipe::with(['kitchen', 'ingredients', 'instructions'])
            ->latest()
            ->paginate(10);

        return RecipeResource::collection($recipes);
    }
}
