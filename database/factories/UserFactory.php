<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
		'username'=> $faker->userName,
        'password' => '$2y$10$7UcQAegtKDR4JUqAz3x8wu.t81NQWEE05J8i.useqYLUPAbo9occW',
		'remember_token' => str_random(10),
		'position_id'=> 7
    ];
});
