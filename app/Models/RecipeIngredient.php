<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class RecipeIngredient
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property int $unit_id
 * @property int $quantity
 * @property string|null $notes
 * @property-read Ingredient $ingredient
 * @property-read Recipe $recipe
 * @property-read UnitOfMeasurement $unit
 */
class RecipeIngredient extends Pivot
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'unit_id',
        'quantity',
        'notes',
    ];

    /** @return BelongsTo<UnitOfMeasurement, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_id');
    }
}
