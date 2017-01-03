<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
      //factory(App\Product::class, 50)->create();

      DB::table('products')->insert([
        [
          'product_name' => 'making 1000 in 2 weeks!',
          'price' => 10,
          'product_description' => 'I will teach you step by step how to make 1000 in 2 weeks!'
        ],

        [
          'product_name' => '[pdf] Email marketing guide',
          'price' => 5,
          'product_description' => 'leaked email marketing guide original cost 49$'
        ]
      ]);

    }
}
