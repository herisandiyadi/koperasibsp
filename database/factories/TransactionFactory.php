<?php

use Faker\Generator as Faker;
use App\DepositTransaction;
use App\DepositTransactionDetail;
use App\Member;
use App\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
		'username'=> $faker->userName,
        'password' => '$2y$10$7UcQAegtKDR4JUqAz3x8wu.t81NQWEE05J8i.useqYLUPAbo9occW',
		'remember_token' => str_random(10),
		'position_id'=> 7
    ];
});

$factory->define(Member::class, function (Faker $faker) {
    return [
        'nik_bsp' => $faker->randomNumber,
		'nik_koperasi' => $faker->randomNumber,
		'project_id' => 1,
		'region_id' => 1,
		'position_id' => 2,
		'id_number' => $faker->randomNumber,
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'dob' => $faker->dateTimeThisCentury->format('Y-m-d'),
		'religion' => 'Islam',
		'gender' => 'Laki-laki',
		'address' => $faker->address,
		'picture' => $faker->imageUrl($width = 640, $height = 480, 'cats'),
		'join_date' => $faker->dateTime($max = 'now', $timezone = null),
		'start_date' => $faker->dateTime($max = 'now', $timezone = null),
		'end_date' => $faker->dateTime($max = 'now', $timezone = null),
		'phone_number' => $faker->phoneNumber,
		'email' => $faker->email,
		'is_active' => 1,
		'special' => 'user',
		'verified_at' =>$faker->dateTime($max = 'now', $timezone = null)
    ];
});

$factory->define(DepositTransaction::class, function (Faker $faker) {
    return [
        'ms_deposit_id' => 1,
		'total_deposit' => $faker->randomNumber(5),
		'post_date' => $faker->dateTime($max = 'now', $timezone = null)
    ];
});

$factory->define(DepositTransactionDetail::class, function (Faker $faker) {
    return [
		'deposit_type' => 'wajib',
		'credit' => $faker->randomNumber(5),
		'total' =>$faker->randomNumber(5)
    ];
});
