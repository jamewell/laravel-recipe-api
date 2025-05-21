<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RecipeIngredient;

/**
 * Class UnitOfMeasurement
 *
 * @property int $id
 * @property string $full_name
 * @property string $abbreviation
 * @property string|null $description
 */
class UnitOfMeasurement extends Model
{
    protected $fillable = [
        'full_name',
        'abbreviation',
        'description',
    ];

    /** @return HasMany<RecipeIngredient, $this> */
    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class, 'unit_id');
    }
}
