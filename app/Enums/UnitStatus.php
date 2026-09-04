<?php

namespace App\Enums;

enum UnitStatus: string
{
    case IMPORTED = 'IMPORTED';

    case ARRIVAL_PENDING = 'ARRIVAL_PENDING';
    case ARRIVAL_COMPLETED = 'ARRIVAL_COMPLETED';

    case ASSEMBLY_PENDING = 'ASSEMBLY_PENDING';
    case ASSEMBLY_COMPLETED = 'ASSEMBLY_COMPLETED';

    case DELIVERY_PENDING = 'DELIVERY_PENDING';

    case COMPLETED = 'COMPLETED';


    public function label(): string
    {
        return match ($this) {
            self::IMPORTED =>
            'Importada',

            self::ARRIVAL_PENDING =>
            'Pendiente de llegada',

            self::ARRIVAL_COMPLETED =>
            'Llegada documentada',

            self::ASSEMBLY_PENDING =>
            'Pendiente de armado',

            self::ASSEMBLY_COMPLETED =>
            'Armado finalizado',

            self::DELIVERY_PENDING =>
            'Pendiente de entrega',

            self::COMPLETED =>
            'Expediente completo',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::IMPORTED =>
            'bg-slate-100 text-slate-700',

            self::ARRIVAL_PENDING =>
            'bg-amber-100 text-amber-700',

            self::ARRIVAL_COMPLETED =>
            'bg-blue-100 text-blue-700',

            self::ASSEMBLY_PENDING =>
            'bg-orange-100 text-orange-700',

            self::ASSEMBLY_COMPLETED =>
            'bg-indigo-100 text-indigo-700',

            self::DELIVERY_PENDING =>
            'bg-violet-100 text-violet-700',

            self::COMPLETED =>
            'bg-emerald-100 text-emerald-700',
        };
    }
}
