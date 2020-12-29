<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cobrander;
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

$factory->define(Cobrander::class, function (Faker $faker) {
    return [
        "COBRANDER_ID" => 99991,
        "AGENT_ID" => 2,
        "COBRANDER_NAME" => "g",
        "OPERATOR_NAME" => "e",
        'CREATED_AT' => now(),
        'UPDATED_AT' => now(),
        'CREATED_BY' => 1,
        'UPDATED_BY' => 1,
    ];
});