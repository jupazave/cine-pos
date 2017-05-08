<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Event::class, function (Faker\Generator $faker) {
    $cast = implode(',',$faker->randomElements([
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
        $faker->name,
    ],random_int(2,7)));

    return [
        'name' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'dramaturgic' => $faker->name,
        'director' => $faker->name,
        'cast' => $cast,
        'email' => $faker->email,
        'facebook' => $faker->url,
        'instagram' => $faker->url,
        'twitter' => $faker->url,
        'webpage' => $faker->url,
        'profile_picture' => $faker->imageUrl(),
        'category_id' => function() {
            return factory(App\Category::class)->create()->id;
        },
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        }
    ];
});