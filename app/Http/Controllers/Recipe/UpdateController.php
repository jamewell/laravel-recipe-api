<?php

namespace App\Http\Controllers\Recipe;

use App\Actions\Recipe\SyncIngredientsAction;
use App\Actions\Recipe\SyncInstructionsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\UpdateRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;

class UpdateController extends Controller
{
    public function __construct(
        private readonly SyncIngredientsAction $syncIngredients,
        private readonly SyncInstructionsAction $syncInstructions
    ) {}

    public function __invoke(UpdateRequest $updateRequest, Recipe $recipe): RecipeResource
    {
        $recipe->update($updateRequest->validated());

        if ($updateRequest->has('ingredients')) {
            $this->syncIngredients->execute($recipe, $updateRequest->input('ingredients'));
        }

        if ($updateRequest->has('instructions')) {
            $this->syncInstructions->execute($recipe, $updateRequest->input('instructions'));
        }

        return RecipeResource::make(
            $recipe->load(['kitchen', 'ingredients', 'instructions'])
        );
    }
}
