<?php

namespace App\Models;

use Database\Factories\IngredientCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class IngredientCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class IngredientCategory extends Model
{
    /** @use HasFactory<IngredientCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /** @return HasMany<Ingredient, $this> */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class, 'category_id');
    }
}
