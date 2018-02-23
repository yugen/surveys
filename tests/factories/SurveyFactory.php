<?php

use Faker\Generator as Faker;

$factory->define(\Sirs\Surveys\Models\Survey::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'version' => 1,
        'file_name' => uniqid().'.xml',
        'response_table' => $faker->unique()->word,
    ];
});
