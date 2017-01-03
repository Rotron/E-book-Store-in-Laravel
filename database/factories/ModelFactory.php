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

$factory->define(App\Post::class, function(Faker\Generator $faker){
  $title = $faker->sentence($nbwords = 5, $variableNbWords = true);

  return [
    'title' => $title,
    'slug' => str_slug($title, '-'),
    'body' => $faker->text($maxNbChars = 500),
  ];
});


$factory->define(App\Product::class, function(Faker\Generator $faker){
  return [
    'product_name' => $faker->words($nb = 3, $asText = false),
    'price' => $faker->randomDigit(),
    'product_description' => $faker->sentence($nbwords = 10),
  ];
});
