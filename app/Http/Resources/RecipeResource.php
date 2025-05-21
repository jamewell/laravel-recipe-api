<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RecipeResource
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $img_url
 * @property int $prep_time
 * @property int $cook_time
 * @property int $servings
 * @property \App\Models\User $user
 * @property \App\Models\Kitchen $kitchen
 * @property \App\Models\Ingredient $ingredients
 * @property \App\Models\Instruction $instructions
 */
class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @property Recipe $resource
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
                'username' => $this->user->user_name,
            ]),
            'kitchen' => KitchenResource::make($this->whenLoaded('kitchen')),
            'ingredients' => RecipeIngredientResource::collection($this->whenLoaded('ingredients')),
            'instructions' => InstructionResource::collection($this->whenLoaded('instructions')),
        ];
    }
}
