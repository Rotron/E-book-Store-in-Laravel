<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\Sale;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
      $this->call(UsersTableSeeder::class);

      factory(App\Product::class, 50)->create()->each(function($product){
        $product->sales()->save(factory(App\Sale::class)->make());
      });

    }
}
