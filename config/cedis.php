<?php

return [

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
