<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use App\Models\Ingredient;
use App\Models\Kitchen;
use App\Models\Instruction;
use App\Models\RecipeIngredient;
use App\Models\User;

/**
 * Class Recipe
 *
 * @property int $id
 * @property int $user_id
 * @property int $kitchen_id
 * @property string $title
 * @property string|null $description
 * @property int|null $prep_time
 * @property int|null $cook_time
 * @property int|null $servings
 * @property string|null $img_url
 * @property-read Kitchen $kitchen
 * @property-read User $user
 */
class Recipe extends Model
{
    protected $fillable = [
        'user_id',
        'kitchen_id',
        'title',
        'description',
        'prep_time',
        'cook_time',
        'servings',
        'img_url',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);

    }

    /** @return BelongsTo<Kitchen, $this> */
    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(Kitchen::class);
    }

    /** @return BelongsToMany<Ingredient, $this, RecipeIngredient> */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit_id', 'notes'])
            ->using(RecipeIngredient::class);
    }

    /** @return Builder */
    public function instructions(): Builder
    {
        return $this->hasMany(Instruction::class)->orderBy('step_number');
    }
}
