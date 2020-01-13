<?php

/**
 * Welcome
 */
$router->get('/', function () use ($router) {
    return response()->json([
        'status' => 'success',
        'data' => 'All good',
    ]);
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
 * Categories
 */
$router->group([
    'prefix' => 'api',
    'middleware' => 'auth',
], function() use($router) {
    $router->group([
        'prefix' => 'categories',
    ], function() use($router) {

        $router->get('/', 'CategoriesController@index');
        $router->get('/{slug}', 'CategoriesController@show');
        $router->put('/{slug}', 'CategoriesController@update');
        $router->post('/', 'CategoriesController@store');
        $router->delete('/{slug}', 'CategoriesController@delete');
        $router->get('/{slug}/expenses', 'CategoriesController@expenses');
    });
});


/**
 * Stats
 */
$router->group([
    'prefix' => 'api',
    'middleware' => 'auth',
], function() use($router) {
    $router->get('/stats/by-category', 'StatsController@byCategory');
    $router->get('/stats/by-month', 'StatsController@byMonth');
    $router->get('/stats/by-day', 'StatsController@byDay');
    $router->get('/stats/monthly-summary', 'StatsController@monthlySummary');
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

