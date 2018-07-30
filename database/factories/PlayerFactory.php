<?php

use Faker\Generator as Faker;
use App\Ranking;

$factory->define(App\Player::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'ranking_id' => factory(Ranking::class)->create()->id
    ];
});
