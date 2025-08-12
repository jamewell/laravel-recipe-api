<?php

namespace App\Data\Recipe;

use App\Enums\UnitType;

class ConversionResult
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly float $original_value,
        public readonly string $original_unit,
        public readonly float $converted_value,
        public readonly string $converted_unit,
        public readonly UnitType $type,
    ) {}
}
