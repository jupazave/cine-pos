<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Review::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'score' => random_int(1,5),
        'event_id' => function() {
            return factory(App\Event::class)->create()->id;
        }
    ];
});
