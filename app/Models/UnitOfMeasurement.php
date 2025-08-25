<?php

namespace App\Models;

use App\Enums\UnitSystem;
use App\Enums\UnitType;
use Database\Factories\UnitOfMeasurementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class UnitOfMeasurement
 *
 * @property int $id
 * @property string $full_name
 * @property string $abbreviation
 * @property string|null $description
 * @property UnitSystem $system
 * @property UnitType $type
 * @property float $base_equivalent
 * @property-read RecipeIngredient[] $recipeIngredients
 *
 * @method static UnitOfMeasurementFactory factory()
 */
class UnitOfMeasurement extends Model
{
    /** @use HasFactory<UnitOfMeasurementFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'abbreviation',
        'description',
        'system',
        'type',
        'base_equivalent',
    ];

    protected $casts = [
        'base_equivalent' => 'float',
        'system' => UnitSystem::class,
        'type' => UnitType::class,
    ];

    /** @return HasMany<RecipeIngredient, $this> */
    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class, 'unit_id');
    }
}
