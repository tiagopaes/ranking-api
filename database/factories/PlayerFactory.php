<?php

use Faker\Generator as Faker;
use App\Models\Ranking;

$factory->define(App\Models\Player::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'ranking_id' => factory(Ranking::class)->create()->id
    ];
});
