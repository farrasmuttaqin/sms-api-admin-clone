<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
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

$factory->define(Client::class, function (Faker $faker) {
    return [
        "CLIENT_ID" => 99991,
        "ARCHIVED_DATE" => now(),
        "COMPANY_NAME" => "g1",
        "COMPANY_URL" => "e",
        "COUNTRY_CODE" => "IDN",
        "CONTACT_NAME" => "e",
        "CONTACT_EMAIL" => "e@e",
        "CONTACT_PHONE" => "6281275642512",
        "CONTACT_ADDRESS" => "e",
        "CUSTOMER_ID" => "e",
        'CREATED_AT' => now(),
        'UPDATED_AT' => now(),
        'CREATED_BY' => 1,
        'UPDATED_BY' => 1,
    ];
});
