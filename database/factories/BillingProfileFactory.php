<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Billing\BillingProfile;
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

$factory->define(BillingProfile::class, function (Faker $faker) {
    return [
        'BILLING_PROFILE_ID'=> 99991,
        'BILLING_TYPE' => 'x',
        'NAME'=> "tes",
        'DESCRIPTION'=> "tes",
        'CREATED_AT' => now(),
        'UPDATED_AT' => now(),
    ];
});
