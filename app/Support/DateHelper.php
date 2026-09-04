<?php

namespace App\Support;

use Carbon\CarbonInterface;

final class DateHelper
{
    public static function local(
        ?CarbonInterface $date
    ): ?CarbonInterface {
        return $date?->copy()->timezone(
            config('cedis.timezone')
        );
    }

    public static function format(
        ?CarbonInterface $date,
        string $format = 'd/m/Y H:i'
    ): string {
        if ($date === null) {
            return '—';
        }

        return self::local($date)
            ->format($format);
    }
}
