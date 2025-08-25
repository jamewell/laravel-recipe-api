<?php

namespace App\Http\Resources;

use App\Enums\UnitSystem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RecipeIngredientResource
 *
 * @property int $id
 * @property string $name
 * @property \App\Models\RecipeIngredient $pivot
 * @property \App\Models\IngredientCategory $category
 * @property \App\Models\UnitOfMeasurement $unit
 */
class RecipeIngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @property \App\Models\RecipeIngredient $resource
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userPreference = $request->user()?->getPreferedUnitSystem() ?? UnitSystem::METRIC;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ]),
            'quantity' => $this->pivot->quantity,
            'unit' => new ConvertibleUnitResource(
                $this->pivot->unit,
                $this->pivot->quantity,
                $userPreference,
            ),
            'notes' => $this->pivot->notes,
        ];
    }
}
