<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

  public function run()
  {
    // Admin
    DB::table('users')->insert([
      'username' => 'admin',
      'password' => Hash::make('admin'),
      'email'   => 'example@gmail.com',
      'role' => 1,
      'remember_token' => '',
    ]);

    // Sample user..
    DB::table('users')->insert([
      'username' => 'user',
      'password' => Hash::make('123456'),
      'email'   => 'phpdevsami@gmail.com',
      'confirmation_code' => '',
      'role' => 2,
      'remember_token' => '',
    ]);
  }
}
