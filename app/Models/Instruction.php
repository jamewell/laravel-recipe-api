<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Instruction
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $step_number
 * @property string $description
 * @property string|null $img_url
 * @property-read Recipe $recipe
 */
class Instruction extends Model
{
    protected $fillable = [
        'recipe_id',
        'step_number',
        'description',
        'img_url',
    ];

    /** @return BelongsTo<Recipe, $this> */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
