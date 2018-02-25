<?php

use Faker\Generator as Faker;

$factory->define(Sirs\Surveys\Test\Stubs\Participant::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName.' '.$faker->lastName
    ];
});
