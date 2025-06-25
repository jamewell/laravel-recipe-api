<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class IngredientCategoryResource
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class IngredientCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @property \App\Models\IngredientCategory $resource
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
