<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Aircraft;
use Faker\Generator as Faker;

$factory->define(Aircraft::class, function (Faker $faker) {
    return [
        'callsign' => 'DE'.$faker->regexify('[A-Z]{3}'),
        'type' => $faker->randomElement(['C172', 'P28A', 'SR20']),
        'load' => $faker->numberBetween(175, 250)
    ];
});
