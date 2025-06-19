<?php

namespace App\Http\Controllers\Recipe;

use App\Actions\Recipe\SyncIngredientsAction;
use App\Actions\Recipe\SyncInstructionsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\StoreRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;

class StoreController extends Controller
{
    public function __construct(
        private readonly SyncIngredientsAction $syncIngredientsAction,
        private readonly SyncInstructionsAction $syncInstructionsAction,
    ) {}

    public function __invoke(StoreRequest $request): RecipeResource
    {
        // @phpstan-ignore-next-line
        $recipe = Recipe::create($request->validated());

        if ($request->has('ingredients')) {
            $this->syncIngredientsAction->execute($recipe, $request->input('ingredients'));
        }

        if ($request->has('instructions')) {
            $this->syncInstructionsAction->execute($recipe, $request->input('instructions'));
        }

        return RecipeResource::make($recipe->load([
            'user',
            'kitchen',
            'ingredients',
            'instructions',
        ]));
    }
}
