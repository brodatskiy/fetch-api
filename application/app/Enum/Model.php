<?php

namespace App\Enum;

enum Model: string
{
    case Order = "Order";
    case Sale = "Sale";
    case Stock = "Stock";
    case Income = "Income";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function fullClass(): string
    {
        return 'App\Models\\' . $this->value;
    }
}