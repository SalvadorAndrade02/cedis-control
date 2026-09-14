<?php

namespace App\Enums;

enum AssemblyWorkStatus: string
{
    case RUNNING = 'RUNNING';
    case PAUSED = 'PAUSED';
    case COMPLETED = 'COMPLETED';

    public function label(): string
    {
        return match ($this) {
            self::RUNNING =>
                'En armado',

            self::PAUSED =>
                'Pausado',

            self::COMPLETED =>
                'Finalizado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::RUNNING =>
                'bg-blue-100 text-blue-700',

            self::PAUSED =>
                'bg-amber-100 text-amber-700',

            self::COMPLETED =>
                'bg-emerald-100 text-emerald-700',
        };
    }
}