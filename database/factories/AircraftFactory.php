<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Aircraft;
use Faker\Generator as Faker;

$factory->define(Aircraft::class, function (Faker $faker) {
    return [
        'callsign' => 'DE'.$faker->regexify('[A-Z]{3}'),
        'load' => $faker->numberBetween(175, 250)
    ];
});
