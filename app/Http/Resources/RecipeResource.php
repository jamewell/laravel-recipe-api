<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'img_url' => $this->img_url,
            'prep_time' => $this->prep_time,
            'cook_time' => $this->cook_time,
            'servings' => $this->servings,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'username' => $this->user->username,
            ]),
            'kitchen' => KitchenResource::make($this->whenLoaded('kitchen')),
            'ingredients' => RecipeIngredientResource::collection($this->whenLoaded('ingredients')),
            'instructions' => InstructionResource::collection($this->whenLoaded('instructions')),
        ];
    }
}
