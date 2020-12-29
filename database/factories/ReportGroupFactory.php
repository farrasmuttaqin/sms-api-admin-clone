<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Billing\ReportGroup;
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

$factory->define(ReportGroup::class, function (Faker $faker) {
    return [
        "BILLING_REPORT_GROUP_ID" => 99991,
        "NAME" => "tes",
        "DESCRIPTION" => "jhkl89",
        "CREATED_AT" => now(),
        "UPDATED_AT" => now(),
    ];
});
