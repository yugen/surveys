<?php

use Faker\Generator as Faker;
use Sirs\Surveys\Test\Stubs\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName.' '.$faker->lastName,
        'email' => $faker->unique()->email,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
