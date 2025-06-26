<?php

namespace App\Http\Controllers\IngredientCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ingredient\StoreCategoryRequest;
use App\Http\Resources\IngredientCategoryResource;
use App\Models\IngredientCategory;

class StoreController extends Controller
{
    public function __invoke(StoreCategoryRequest $request): IngredientCategoryResource
    {
        $category = IngredientCategory::create($request->validated());

        return new IngredientCategoryResource($category);
    }
}
