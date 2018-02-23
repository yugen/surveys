<?php

use Faker\Generator as Faker;
use Sirs\Surveys\Test\Stubs\User;

$factory->define(\Sirs\Surveys\Models\Response::class, function (Faker $faker) {
    return [
        'survey_id' => factory(\Sirs\Surveys\Models\Survey::class)->create()->id,
        'respondent_id' => factory(User::class)->create()->id,
        'respondent_type' => User::class,
    ];
});
