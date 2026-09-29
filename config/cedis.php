<?php

return [

    'allow_permanent_unit_delete' =>
        env('CEDIS_ALLOW_PERMANENT_UNIT_DELETE', false),

    /*
    |--------------------------------------------------------------------------
    | Zona horaria operacional
    |--------------------------------------------------------------------------
    |
    | Las fechas se almacenan internamente en UTC y se convierten
    | a esta zona solamente para presentación.
    |
    */

    'timezone' => env(
        'APP_DISPLAY_TIMEZONE',
        'America/Monterrey'
    ),

];
