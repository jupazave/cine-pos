<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Theater::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'address' => $faker->address,
        'zip_code' => $faker->postcode,
        'city' => $faker->city,
        'country' => $faker->country,
        'email' => $faker->email,
        'facebook' => $faker->url,
        'instagram' => $faker->url,
        'twitter' => $faker->url,
        'webpage' => $faker->url,
        'profile_picture' => $faker->imageUrl(),
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        }
    ];
});