<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class InstructionResource
 *
 * @property int $id
 * @property string $description
 * @property int $order
 * @property string|null $img_url
 */
class InstructionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @property \App\Models\Instruction $resource
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'description' => $this->description,
            'img_url' => $this->img_url,
        ];
    }
}
