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

$factory->define(App\Product::class, function(Faker\Generator $faker){
  $product_name = $faker->sentence($nbWords = 6, $variableNbWords = true);
  return [
    'product_name' => $product_name,
    'slug' => str_slug($product_name, '-'),
    'product_price' => $faker->randomDigit(),
    'product_description' => $faker->sentence($nbwords = 10),
  ];
});

$factory->define(App\Sale::class, function(Faker\Generator $faker){
  return [
    'buyers_name' => $faker->name,
    'buyers_email' => $faker->email,
    'transaction_id' => $faker->uuid,
  ];
});
