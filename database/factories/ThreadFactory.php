<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'created_by' => factory(User::class)->create()->id,
        'title' => str_random(25),
        'content' => $faker->paragraph,
    ];
});
