<?php

namespace App\Enum;

enum Model: string
{
    case Order = "App\Models\Order";
    case Sale = "App\Models\Sale";
    case Stock = "App\Models\Stock";
    case Income = "App\Models\Income";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}