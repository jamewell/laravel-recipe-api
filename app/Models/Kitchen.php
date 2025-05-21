<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class IngredientCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class Kitchen extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /** @return HasMany<Recipe, $this> */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }
}
