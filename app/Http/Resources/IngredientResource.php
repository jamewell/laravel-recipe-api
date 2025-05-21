<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class IngredientResource
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 */
class IngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @property \App\Models\Ingredient $resource
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
