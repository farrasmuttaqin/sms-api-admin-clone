<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sender;
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

$factory->define(Sender::class, function (Faker $faker) {
    return [
        "SENDER_ID" => 991,
        "SENDER_ENABLED" => 1,
        "USER_ID" => 99991,
        "SENDER_NAME" => "sender_name_1",
        "COBRANDER_ID" => 5,
    ];
});