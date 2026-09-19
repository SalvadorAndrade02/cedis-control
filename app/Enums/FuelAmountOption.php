<?php

namespace App\Enums;

enum FuelAmountOption: string
{
    case FIXED = 'FIXED';
    case OTHER = 'OTHER';

    public function label(): string
    {
        return match ($this) {
            self::FIXED =>
                'Importe predefinido',

            self::OTHER =>
                'Otro importe',
        };
    }
}