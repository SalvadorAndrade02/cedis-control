<?php

namespace App\Enums;

enum AssemblyPauseReason: string
{
    case MEAL = 'MEAL';

    case PRIORITY_CHANGE =
    'PRIORITY_CHANGE';

    case END_OF_SHIFT =
    'END_OF_SHIFT';

    public function label(): string
    {
        return match ($this) {
            self::MEAL =>
                'Hora de comida',

            self::PRIORITY_CHANGE =>
                'Cambio de prioridad',

            self::END_OF_SHIFT =>
                'Fin de jornada laboral',
        };
    }
}