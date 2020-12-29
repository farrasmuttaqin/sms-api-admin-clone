<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Credit;
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

$factory->define(Credit::class, function (Faker $faker) {
    return [
        'CREDIT_TRANSACTION_ID' => 991,
        'TRANSACTION_REF' => "T20201007sekU9",
        'USER_ID' => 99991,
        'CREDIT_REQUESTER' => "farras",
        'CREDIT_AMOUNT' => 200,
        'CREDIT_PRICE' => 300,
        'CURRENCY_CODE' => "rp",
        'CURRENT_BALANCE' => 400,
        'PREVIOUS_BALANCE' => 500,
        'PAYMENT_METHOD' => "bank",
        'PAYMENT_DATE' => now(),
        'PAYMENT_ACK' => 0,
        'CREATED_BY' => 1,
        'CREATED_DATE' => now(),
    ];
});