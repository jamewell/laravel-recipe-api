<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;

class ShowController extends Controller
{
    public function __invoke(Recipe $recipe): RecipeResource
    {
        return RecipeResource::make(
            $recipe->load(['kitchen', 'ingredients', 'instructions'])
        );
    }
}
