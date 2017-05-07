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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    $roles = ['admin', 'user'];

    return [
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role' => $faker->randomElements($roles, 1)[0],
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthdate' => $faker->dateTimeThisCentury,
        'phone1' => $faker->phoneNumber,
        'phone2' => $faker->phoneNumber,
        'address' => $faker->address,
        'zipcode' => $faker->postcode,
        'city' => $faker->city,
        'country' => $faker->country,
        'profile_picture' => $faker->imageUrl()
    ];
});
