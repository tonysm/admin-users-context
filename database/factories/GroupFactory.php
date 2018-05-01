<?php

use Faker\Generator as Faker;

$factory->define(\App\Users\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
