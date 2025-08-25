<?php

namespace App\Http\Resources;

use App\Enums\UnitSystem;
use App\Enums\UnitType;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UnitOfMeasurementResource
 *
 * @property int $id
 * @property string $full_name
 * @property string $abbreviation
 * @property string|null $description
 * @property UnitSystem $system
 * @property UnitType $type
 * @property UnitOfMeasurement $resource
 */
class UnitOfMeasurementResource extends JsonResource
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
            'full_name' => $this->full_name,
            'abbreviation' => $this->abbreviation,
            'description' => $this->description,
            'system' => $this->system->value,
            'type' => $this->type->value,
        ];
    }
}
