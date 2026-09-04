<?php

namespace App\Enums;

enum MilestoneStatus: string
{
    case PENDING = 'PENDING';

    case IN_PROGRESS = 'IN_PROGRESS';

    case COMPLETED = 'COMPLETED';


    public function label(): string
    {
        return match ($this) {
            self::PENDING =>
            'Pendiente',

            self::IN_PROGRESS =>
            'En proceso',

            self::COMPLETED =>
            'Completado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING =>
            'bg-slate-100 text-slate-600',

            self::IN_PROGRESS =>
            'bg-amber-100 text-amber-700',

            self::COMPLETED =>
            'bg-emerald-100 text-emerald-700',
        };
    }
}
