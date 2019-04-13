<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/**
 * Expenses
 */
$router->group([
    'prefix' => 'api',
    'middleware' => 'auth',
], function() use($router) {
    $router->group([
        'prefix' => 'expenses',
    ], function() use($router) {

        $router->get('/', 'ExpensesController@index');
        $router->get('/{expense}', 'ExpensesController@show');
        $router->put('/{expense}', 'ExpensesController@update');
        $router->post('/', 'ExpensesController@store');
        $router->delete('/{expense}', 'ExpensesController@delete');
    });
});


/**
 * Stats
 */
$router->group([
    'prefix' => 'api',
    // 'middleware' => 'auth', // TODO after FE resource building w/ proper auth headers setup
], function() use($router) {
    $router->get('/stats/by-category', 'StatsController@byCategory');
    $router->get('/stats/by-month', 'StatsController@byMonth');
});

/**
 * Authentication
 */
$router->group([
    'prefix' => 'auth',
], function() use($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
});






$router->get('/dummy', function () use ($router) {
    // TODO seeders
    /*
    $user = \App\User::create([
        'email' => 'richard.trujillo.torres@gmail.com',
        // 'password' => \Illuminate\Support\Facades\Hash::make('secret'),
        'password' => app('hash')->make('secret'),
    ]);


    dd($user);
    */









    // check hash
    // $hash = \Illuminate\Support\Facades\Hash::make('secret');
    $hash = app('hash')->make('secret');
    $user = \App\User::findOrFail(3);

    dump($user->password);
    dump($hash);
    dd($user->password == $hash);

});

// test resource
$router->group([
    'prefix' => 'api',
    'middleware' => 'auth',
    // 'as' => 'api.',
], function() use($router) {
    $router->get('/', function() {
        return response()->json([
            'status' => 'succeeeess',
            'data' => 'All good',
        ]);
    });
});


