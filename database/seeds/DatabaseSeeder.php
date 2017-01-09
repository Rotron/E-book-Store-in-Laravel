<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\Sale;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
      $this->call(UsersTableSeeder::class);
    }
}
