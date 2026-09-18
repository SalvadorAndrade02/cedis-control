<?php

return [

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'array' => 'El campo :attribute debe ser un conjunto.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => 'El campo :attribute no contiene una fecha válida.',
    'email' => 'El campo :attribute debe contener un correo electrónico válido.',
    'exists' => 'El valor seleccionado para :attribute no es válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'file' => 'El archivo :attribute no debe superar :max kilobytes.',
        'string' => 'El campo :attribute no debe contener más de :max caracteres.',
        'array' => 'El campo :attribute no debe contener más de :max elementos.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'file' => 'El archivo :attribute debe tener al menos :min kilobytes.',
        'string' => 'El campo :attribute debe contener al menos :min caracteres.',
        'array' => 'El campo :attribute debe contener al menos :min elementos.',
    ],
    'nullable' => 'El campo :attribute puede estar vacío.',
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'El valor de :attribute ya está registrado.',

    /*
    |--------------------------------------------------------------------------
    | CONTRASEÑAS
    |--------------------------------------------------------------------------
    */

    'password' => [
        'letters' =>
            'La contraseña debe contener al menos una letra.',

        'mixed' =>
            'La contraseña debe contener al menos una letra mayúscula y una minúscula.',

        'numbers' =>
            'La contraseña debe contener al menos un número.',

        'symbols' =>
            'La contraseña debe contener al menos un símbolo.',

        'uncompromised' =>
            'La contraseña indicada apareció en una filtración de datos. Selecciona una diferente.',
    ],


    /*
    |--------------------------------------------------------------------------
    | NOMBRES AMIGABLES
    |--------------------------------------------------------------------------
    */

    'attributes' => [

        'name' =>
            'nombre',

        'email' =>
            'correo electrónico',

        'role' =>
            'rol',

        'password' =>
            'contraseña',

        'password_confirmation' =>
            'confirmación de contraseña',

    ],

];