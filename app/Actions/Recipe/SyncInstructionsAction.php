<?php

namespace App\Actions\Recipe;

use App\Models\Recipe;

class SyncInstructionsAction
{
    /**
     * Sync the instructions for a given recipe.
     *
     * @param  array<int, mixed>  $instructions
     */
    public function execute(Recipe $recipe, array $instructions): void
    {
        $recipe->instructions()->delete();

        $instructionsToCreate = [];
        foreach ($instructions as $index => $instruction) {
            $stepNumber = $index + 1;

            $instructionsToCreate[] = [
                'recipe_id' => $recipe->id,
                'description' => $instruction['description'],
                'step_number' => $stepNumber,
            ];
        }

        $recipe->instructions()->createMany($instructionsToCreate);
    }
}
