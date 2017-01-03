<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
      $this->call(PostsTableSeeder::class);
      $this->call(ProductsTableSeeder::class);
    }
}
