<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(\App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'slug' => $faker->slug,
        'needed' => $faker->boolean,
    ];
});

$factory->define(\App\Expense::class, function (Faker\Generator $faker) {
    return [
        'denomination' => $faker->numberBetween(1, 100),
        'description' => $faker->sentence,
//        'category' => $faker->numberBetween(1, 3),
    ];
});
