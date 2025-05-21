<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class KitchenResource
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class KitchenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @property \App\Models\Kitchen $resource
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
