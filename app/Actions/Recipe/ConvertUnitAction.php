<?php

namespace App\Actions\Recipe;

use App\Data\Recipe\ConversionResult;
use App\Models\UnitOfMeasurement;
use Brick\Math\Exception\DivisionByZeroException;
use InvalidArgumentException;

class ConvertUnitAction
{
    public function execute(
        float $value,
        UnitOfMeasurement $fromUnit,
        UnitOfMeasurement $toUnit,
        ?int $precision = 2,
    ): ConversionResult {
        if ($fromUnit->type !== $toUnit->type) {
            throw new InvalidArgumentException('Cannot convert between different unit types');
        }

        if ($fromUnit->base_equivalent === null || $toUnit->base_equivalent === null) {
            throw new InvalidArgumentException('Conversion not supported for one or both units.');
        }

        if (is_infinite($value) || $value < 0) {
            throw new InvalidArgumentException('Value must be a positive finite number');
        }

        if ($toUnit->base_equivalent === 0.0) {
            throw new DivisionByZeroException('Cannot convert to a unit with a base equivalent of zero.');
        }

        $inBase = $value * $fromUnit->base_equivalent;
        $converted = $inBase / $toUnit->base_equivalent;

        $finalValue = is_null($precision) ? $converted : round($converted, $precision);

        return new ConversionResult(
            original_value: $value,
            original_unit: $fromUnit->abbreviation,
            converted_value: $finalValue,
            converted_unit: $toUnit->abbreviation,
            type: $fromUnit->type,
        );
    }
}
