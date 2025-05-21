<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UnitOfMeasurement;

/**
 * Class UnitOfMeasurementResource
 *
 * @property int $id
 * @property string $full_name
 * @property string $abbreviation
 * @property string|null $description
 */
class UnitOfMeasurementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @property UnitOfMeasurement $resource
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->full_name,
            'abbreviation' => $this->abbreviation,
            'description' => $this->description,
        ];
    }
}
