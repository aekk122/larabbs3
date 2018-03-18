<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {

	$date_time = $faker->dateTimeThisMonth();

    return [
        // 'name' => $faker->name,
        'content' => $faker->sentence(),
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
