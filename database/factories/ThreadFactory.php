<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'created_by' => factory(User::class)->create()->id,
        'title' => ucwords($faker->words(3, true)),
        'content' => $faker->paragraph,
    ];
});
