<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Theater::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'address' => $faker->address,
        'zip_code' => $faker->postcode,
        'town' => $faker->city,
        'province' => $faker->citySuffix,
        'country' => $faker->country
    ];
});