<?php

use Faker\Generator as Faker;
use App\Thread;
use App\User;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => factory(Thread::class)->create()->id,
        'created_by' => factory(User::class)->create()->id,
        'content' => $faker->paragraph,
    ];
});
