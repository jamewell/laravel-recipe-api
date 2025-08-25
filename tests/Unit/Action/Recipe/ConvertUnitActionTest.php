<?php

namespace Tests\Unit\Action\Recipe;

use App\Actions\Recipe\ConvertUnitAction;
use App\Data\Recipe\ConversionResult;
use App\Enums\UnitType;
use App\Models\UnitOfMeasurement;
use Brick\Math\Exception\DivisionByZeroException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ConvertUnitActionTest extends TestCase
{
    use RefreshDatabase;

    private ConvertUnitAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ConvertUnitAction;
    }

    public function test_conversion_between_units_of_the_same_type(): void
    {
        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'kg',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1000.0,
        ]);
        $result = $this->action->execute(1000.0, $fromUnit, $toUnit);

        $this->assertInstanceOf(ConversionResult::class, $result);
        $this->assertEquals(1000.0, $result->original_value);
        $this->assertEquals('g', $result->original_unit);
        $this->assertEquals(1.0, $result->converted_value);
        $this->assertEquals('kg', $result->converted_unit);
        $this->assertEquals(UnitType::WEIGHT, $result->type);
    }

    public function test_conversion_with_precision(): void
    {
        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'ml',
            'type' => UnitType::VOLUME,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'l',
            'type' => UnitType::VOLUME,
            'base_equivalent' => 1000.0,
        ]);
        $result = $this->action->execute(1234.567, $fromUnit, $toUnit, 2);

        $this->assertEquals(1.23, $result->converted_value);
    }

    public function test_conversion_without_precision(): void
    {
        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'ml',
            'type' => UnitType::VOLUME,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'l',
            'type' => UnitType::VOLUME,
            'base_equivalent' => 1000.0,
        ]);
        $result = $this->action->execute(1234.567, $fromUnit, $toUnit, null);

        $this->assertEquals(1.234567, $result->converted_value);
    }

    public function test_conversion_to_same_unit(): void
    {
        $unit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $result = $this->action->execute(100.0, $unit, $unit);

        $this->assertEquals(100.0, $result->converted_value);
    }

    public function test_conversion_with_zero_value(): void
    {
        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'kg',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1000.0,
        ]);
        $result = $this->action->execute(0.0, $fromUnit, $toUnit);

        $this->assertEquals(0.0, $result->converted_value);
    }

    public function test_conversion_between_different_unit_types_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot convert between different unit types');

        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'ml',
            'type' => UnitType::VOLUME,
            'base_equivalent' => 1.0,
        ]);

        $this->action->execute(100.0, $fromUnit, $toUnit);
    }

    public function test_conversion_with_null_base_equivalent_on_from_unit_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Conversion not supported for one or both units.');

        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'count',
            'type' => UnitType::COUNT,
            'base_equivalent' => null,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'count',
            'type' => UnitType::COUNT,
            'base_equivalent' => 1.0,
        ]);

        $this->action->execute(10.0, $fromUnit, $toUnit);
    }

    public function test_conversion_with_null_base_equivalent_on_to_unit_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Conversion not supported for one or both units.');

        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'count',
            'type' => UnitType::COUNT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'count',
            'type' => UnitType::COUNT,
            'base_equivalent' => null,
        ]);

        $this->action->execute(10.0, $fromUnit, $toUnit);
    }

    public function test_negative_value_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be a positive finite number');

        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'kg',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1000.0,
        ]);

        $this->action->execute(-100.0, $fromUnit, $toUnit);
    }

    public function test_infinite_value_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be a positive finite number');

        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'kg',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1000.0,
        ]);

        $this->action->execute(INF, $fromUnit, $toUnit);
    }

    public function test_conversion_to_unit_with_zero_base_equivalent_throws_exception(): void
    {
        $this->expectException(DivisionByZeroException::class);
        $this->expectExceptionMessage('Cannot convert to a unit with a base equivalent of zero.');

        $fromUnit = new UnitOfMeasurement([
            'abbreviation' => 'g',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 1.0,
        ]);
        $toUnit = new UnitOfMeasurement([
            'abbreviation' => 'invalid_unit',
            'type' => UnitType::WEIGHT,
            'base_equivalent' => 0.0,
        ]);

        $this->action->execute(100.0, $fromUnit, $toUnit);
    }
}
