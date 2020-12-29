<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\VirtualNumber;
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

$factory->define(VirtualNumber::class, function (Faker $faker) {
    return [
        "VIRTUAL_NUMBER_ID" => 991,
        "USER_ID" => 1,
        "DESTINATION" => "081296842422",
        "FORWARD_URL" => "https://google.com",
        'URL_INVALID_COUNT' => 1,
        'URL_ACTIVE' => 1,
    ];
});
