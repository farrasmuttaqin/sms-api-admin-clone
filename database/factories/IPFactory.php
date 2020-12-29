<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\IPRestrictions;
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

$factory->define(IPRestrictions::class, function (Faker $faker) {
    return [
        "USER_IP_ID" => 991,
        "IP_ADDRESS" => '192.168.1.1',
        "USER_ID" => 99991,
    ];
});