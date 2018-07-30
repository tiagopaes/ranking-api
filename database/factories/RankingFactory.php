<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(App\Ranking::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => factory(User::class)->create()->id
    ];
});
