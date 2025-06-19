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
        $syncData = [];
        foreach ($ingredients as $ingredient) {
            if (isset($ingredient['id'], $ingredient['quantity'], $ingredient['unit_id'])) {
                $syncData[$ingredient['id']] = [
                    'quantity' => $ingredient['quantity'],
                    'unit_id' => $ingredient['unit_id'],
                    'notes' => $ingredient['notes'] ?? null,
                ];
            }
        }

        $recipe->ingredients()->sync(
            $syncData
        );
    }
}
