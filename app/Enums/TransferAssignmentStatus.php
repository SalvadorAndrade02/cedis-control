<?php

namespace App\Enums;

enum TransferAssignmentStatus: string
{
    case ASSIGNED = 'ASSIGNED';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::ASSIGNED =>
                'Asignado',

            self::COMPLETED =>
                'Completado',

            self::CANCELLED =>
                'Cancelado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::ASSIGNED =>
                'bg-blue-100 text-blue-700',

            self::COMPLETED =>
                'bg-emerald-100 text-emerald-700',

            self::CANCELLED =>
                'bg-slate-100 text-slate-600',
        };
    }
}