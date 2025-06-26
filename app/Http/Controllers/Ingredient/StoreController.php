<?php

namespace App\Http\Controllers\Ingredient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ingredient\StoreRequest;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): IngredientResource
    {
        $ingredient = Ingredient::create($request->validated());

        return new IngredientResource($ingredient->load('category'));
    }
}
