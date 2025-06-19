<?php

namespace App\Models;

use Database\Factories\KitchenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class IngredientCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @method static KitchenFactory factory()
 */
class Kitchen extends Model
{
    /** @use HasFactory<KitchenFactory> */
    use HasFactory;

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
