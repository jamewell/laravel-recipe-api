<?php

namespace App\Actions\Recipe;

use App\Models\Recipe;

class SyncIngredientsAction
{
    /**
     * Sync the ingredients for a given recipe.
     *
     * @param  array<int, mixed>  $ingredients
     */
    public function execute(Recipe $recipe, array $ingredients): void
    {
        $recipe->ingredients()->sync(
            collect($ingredients)->map(function ($ingredient) {
                return [
                    'quantity' => $ingredient['quantity'],
                    'unit_id' => $ingredient['unit_id'],
                    'notes' => $ingredient['notes'] ?? null,
                ];
            })->toArray()
        );
    }
}
