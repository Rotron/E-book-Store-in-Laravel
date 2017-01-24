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
$factory->define(App\User, function(Faker\Generator $faker){
  return [
    'username' => $faker->userName,
    'email' => $faker->email,
    'role' => $faker->numberBetween($min = 0, $max = 3)
    'confirmation_code' => $faker->md5,
    'password' => Hash::make($faker->password());
    'created_at' => dateTime($max = 'now', $timezone = date_default_timezone_get()),
    'updated_at' => dateTime($max = 'now', $timezone = date_default_timezone_get()),
  ];
});
