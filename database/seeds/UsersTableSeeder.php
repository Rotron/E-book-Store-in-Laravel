<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

  public function run()
  {
    DB::table('users')->insert([
      'username' => 'admin',
      'password' => Hash::make('admin'),
      'email'   => 'example@gmail.com',
      'role' => 1,
      'remember_token' => '',
    ]);

    DB::table('users')->insert([
      'username' => 'verax',
      'password' => Hash::make('verax'),
      'email'   => 'phpdevsami@gmail.com',
      'role' => 2,
      'remember_token' => '',
    ]);
  }
}
