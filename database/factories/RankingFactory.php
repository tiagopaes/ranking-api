<?php

use Faker\Generator as Faker;
use App\Models\User;

$factory->define(App\Models\Ranking::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => factory(User::class)->create()->id
    ];
});
