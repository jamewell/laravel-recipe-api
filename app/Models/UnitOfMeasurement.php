<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    protected $fillable = [
        'full_name',
        'abbreviation',
        'description'
    ];

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class, 'unit_id');
    }
}
