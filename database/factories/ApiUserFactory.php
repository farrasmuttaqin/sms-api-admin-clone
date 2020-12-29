<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ApiUser;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(ApiUser::class, function (Faker $faker) {
    return [
        "USER_ID" => 99991,
        "version" => 2,
        "CLIENT_ID" => 1,
        "USER_NAME" => "user_username_1",
        "PASSWORD" => "user_username_1",
        "COBRANDER_ID" => 5,
        "CREDIT" => 1,
        "ACTIVE" => 1,
        "URL_ACTIVE" => 1,
        'DELIVERY_STATUS_URL' => 'https://google.com',
        'IS_POSTPAID' => 1,
        'IS_OJK' => 1,
        'CREATED_DATE' => now(),
        'CREATED_BY' => 1,
    ];
});