<?php

namespace App\Support;

class DurationHelper
{
    public static function format(
        int $seconds
    ): string {

        $seconds = max(
            0,
            $seconds
        );

        $hours =
            intdiv(
                $seconds,
                3600
            );

        $minutes =
            intdiv(
                $seconds % 3600,
                60
            );

        $remainingSeconds =
            $seconds % 60;


        return sprintf(
            '%02d:%02d:%02d',
            $hours,
            $minutes,
            $remainingSeconds
        );
    }
}