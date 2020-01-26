<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Auth configuration
    |--------------------------------------------------------------------------
    |
    | //
    |
    */
    'defaults' => [
        'guard'     => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver'   => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            // 'model'  => app('config')->get('jwt.user'),
            'model'  => \App\User::class,
        ],
    ],
];
