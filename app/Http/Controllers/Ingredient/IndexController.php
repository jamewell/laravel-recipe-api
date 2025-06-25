<?php

namespace App\Http\Controllers\Ingredient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ingredient\IndexRequest;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        $request->validated();

        $ingredients = Ingredient::with('category')
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return IngredientResource::collection($ingredients);
    }
}
