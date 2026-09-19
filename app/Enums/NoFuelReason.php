<?php

namespace App\Enums;

enum NoFuelReason: string
{
    case NO_CREDIT = 'NO_CREDIT';

    case GAS_STATION_UNAVAILABLE =
    'GAS_STATION_UNAVAILABLE';

    case ALREADY_HAS_FUEL =
    'ALREADY_HAS_FUEL';

    case OTHER = 'OTHER';

    public function label(): string
    {
        return match ($this) {
            self::NO_CREDIT =>
                'Sin crédito o saldo insuficiente',

            self::GAS_STATION_UNAVAILABLE =>
                'Gasolinera sin servicio o no atendieron',

            self::ALREADY_HAS_FUEL =>
                'La unidad ya contaba con gasolina',

            self::OTHER =>
                'Otro motivo',
        };
    }
}