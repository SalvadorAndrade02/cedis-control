<?php

namespace App\Enums;

enum MilestoneStage: string
{
    case ARRIVAL = 'ARRIVAL';

    case ASSEMBLY_COMPLETED = 'ASSEMBLY_COMPLETED';

    case CARRIER_DELIVERY = 'CARRIER_DELIVERY';


    public function label(): string
    {
        return match ($this) {
            self::ARRIVAL =>
            'Llegada al CEDIS',

            self::ASSEMBLY_COMPLETED =>
            'Armado finalizado',

            self::CARRIER_DELIVERY =>
            'Entrega a transportadora',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::ARRIVAL =>
            'Llegada',

            self::ASSEMBLY_COMPLETED =>
            'Armado finalizado',

            self::CARRIER_DELIVERY =>
            'Entrega',
        };
    }
}
