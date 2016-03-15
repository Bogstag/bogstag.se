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
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Step::class, function (Faker\Generator $faker) {
    return [
        'steps'    => $faker->randomDigitNotNull,
        'duration' => $faker->randomDigitNotNull,
        'date' => $faker->date('Y-m-d'),
        'created_at' => $faker->date('Y-m-d 00:00:00'),
        'updated_at' => $faker->date('Y-m-d 00:00:00'),
    ];
});

$factory->define(App\Emailstat::class, function (Faker\Generator $faker) {
    return [
        'event'  => $faker->randomElement(['delivered', 'complained', 'bounced', 'dropped']),
        'domain' => $faker->randomElement(['a', 'b', 'c']),
        'count'  => $faker->randomDigitNotNull,
        'date'   => $faker->date('Y-m-d H:i:s'),
    ];
});

$factory->define(App\Date::class, function (Faker\Generator $faker) {
    return [
        'date_id'       => $faker->date('Ymd'),
        'date'          => $faker->date('Y-m-d'),
        'year'          => $faker->date('Y'),
        'month'         => $faker->date('m'),
        'fullmonth'     => $faker->date('F'),
        'shortmonth'    => $faker->date('M'),
        'day'           => $faker->date('d'),
        'fullday'       => $faker->date('l'),
        'shortday'      => $faker->date('D'),
        'dayofweek'     => $faker->date('N'),
        'week'          => $faker->date('W'),
        'nrdaysinmonth' => $faker->date('t'),
        'leapyear'      => $faker->date('L'),
    ];
});

$factory->define(App\Emaildrop::class, function (Faker\Generator $faker) {
    return [
        'sender'    => $faker->safeEmail,
        'subject'   => $faker->sentence(4),
        'spf'       => $faker->randomElement(['Pass', 'Neutral', 'Fail', 'SoftFail']),
        'Spamscore' => $faker->randomFloat(1, 0, 20),
        'Spamflag'  => $faker->randomElement(['Yes', 'No']),
        'DkimCheck' => $faker->randomElement(['Pass', 'Fail']),
        'public'    => $faker->boolean(60),
        'recipient' => $faker->safeEmail,
        'bodyplain' => $faker->paragraph(2),
    ];
});
