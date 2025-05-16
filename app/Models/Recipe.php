<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);

    }

    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(Kitchen::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit_id', 'notes'])
            ->using(RecipeIngredient::class);
    }

    public function instructions(): HasMany
    {
        return $this->hasMany(Instruction::class)->orderBy('step_number');
    }
}
