<?php

namespace App\Http\Resources;

use App\Actions\Recipe\ConvertUnitAction;
use App\Enums\UnitSystem;
use App\Enums\UnitType;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $full_name
 * @property string $abbreviation
 * @property string|null $description
 * @property float|null $base_equivalent
 * @property UnitSystem $system
 * @property UnitType $type
 * @property UnitOfMeasurement $resource
 */
class ConvertibleUnitResource extends JsonResource
{
    public function __construct(
        $resource,
        private float $amount,
        private UnitSystem $userPreference,
    ) {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'abbreviation' => $this->abbreviation,
            'system' => $this->system->value,
            'type' => $this->type->value,
        ];

        if ($this->shouldConvert()) {
            $conversion = $this->getConversion();
            if (! empty($conversion)) {
                $data['converted'] = $conversion;
            }
        }

        return $data;
    }

    private function shouldConvert(): bool
    {
        return $this->system !== UnitSystem::UNIVERSAL
            && $this->system !== $this->userPreference
            && $this->base_equivalent !== null;
    }

    /**
     * @return array<string, mixed>
     */
    private function getConversion(): array
    {
        try {
            $targetUnit = UnitOfMeasurement::where('type', $this->type->value)
                ->where('system', $this->userPreference)
                ->firstOrFail();

            $result = app(ConvertUnitAction::class)->execute(
                $this->amount,
                $this->resource,
                $targetUnit
            );

            return [
                'amount' => $result->converted_value,
                'unit' => $result->converted_unit,
                'full_name' => $targetUnit->full_name,
            ];
        } catch (\Throwable $th) {
            logger()->warning('Unit conversion failed', [
                'error' => $th->getMessage(),
                'original_unit' => $this->abbreviation,
                'target_system' => $this->userPreference->value,
                'amount' => $this->amount,
            ]);

            return [];
        }
    }
}
