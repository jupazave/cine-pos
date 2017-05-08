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
$factory->define(App\Schedule::class, function (Faker\Generator $faker) {
    $startDate = $faker->dateTimeThisMonth;

    $endDate = $startDate->add(new DateInterval('PT10H'));

    return [
        'stage' => $faker->word,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'event_id' => function() {
            return factory(App\Event::class)->create()->id;
        },
        'theater_id' => function() {
            return factory(App\Theater::class)->create()->id;
        }
    ];
});
