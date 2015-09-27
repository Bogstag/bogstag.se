<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Step::class, function (Faker\Generator $faker) {
    $format = null;
    $max = null;
    return [
        'step_id' => $faker->date($format = 'YmdH', $max = 'now'),
        'date_id' => $faker->date($format = 'Ymd', $max = 'now'),
        'steps' => $faker->randomDigitNotNull,
        'duration' => $faker->randomDigitNotNull,
        'datetime' => $faker->date($format = 'Y-m-d 00:00:00', $max = 'now'),
    ];
});

$factory->define(App\Emailstat::class, function (Faker\Generator $faker) {
    $format = null;
    $max = null;
    $array = null;
    return [
        'event' => $faker->randomElement($array = array ('delivered','complained','bounced','dropped')),
        'domain' => $faker->randomElement($array = array ('a','b','c')),
        'count' => $faker->randomDigitNotNull,
        'date' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
    ];
});

$factory->define(App\Date::class, function (Faker\Generator $faker) {
    $format = null;
    $max = null;
    return [
        'date_id' => $faker->date($format = 'Ymd', $max = 'now'),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'year' => $faker->date($format = 'Y', $max = 'now'),
        'month' => $faker->date($format = 'm', $max = 'now'),
        'fullmonth' => $faker->date($format = 'F', $max = 'now'),
        'shortmonth' => $faker->date($format = 'M', $max = 'now'),
        'day' => $faker->date($format = 'd', $max = 'now'),
        'fullday' => $faker->date($format = 'l', $max = 'now'),
        'shortday' => $faker->date($format = 'D', $max = 'now'),
        'dayofweek' => $faker->date($format = 'N', $max = 'now'),
        'week' => $faker->date($format = 'W', $max = 'now'),
        'nrdaysinmonth' => $faker->date($format = 't', $max = 'now'),
        'leapyear' => $faker->date($format = 'L', $max = 'now'),
    ];
});
