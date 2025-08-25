<?php

namespace App\Enums;

enum UnitSystem: string
{
    case METRIC = 'metric';
    case IMPERIAL = 'imperial';
    case UNIVERSAL = 'universal';
}
