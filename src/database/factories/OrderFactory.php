<?php
use App\Http\Model\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'start_latitude' => (string)$faker->latitude,
        'start_longitude' => (string)$faker->longitude,
        'end_latitude' => (string)$faker->latitude,
        'end_longitude' => (string)$faker->longitude,
        'distance'      => rand(1,10000),
        'status' => $faker->randomElement(['UNASSIGNED','TAKEN'])
    ];
});
