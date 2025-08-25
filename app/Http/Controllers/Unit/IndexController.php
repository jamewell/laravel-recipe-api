<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitOfMeasurementResource;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return UnitOfMeasurementResource::collection(
            UnitOfMeasurement::orderBy('type')->orderBy('name')->get()
        );
    }
}
