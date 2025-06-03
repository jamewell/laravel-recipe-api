<?php

namespace App\Models;

use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Ingredient
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property-read IngredientCategory $category
 *
 * @method static IngredientFactory factory()
 */
class Ingredient extends Model
{
    /** @use HasFactory<IngredientFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
    ];

    /** @return BelongsTo<IngredientCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(IngredientCategory::class, 'category_id');
    }

    /** @return BelongsToMany<Recipe, $this, RecipeIngredient> */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit_id', 'notes'])
            ->using(RecipeIngredient::class);
    }
}
