<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersTableCreate extends Migration
{

  public function up()
  {
    Schema::create('users', function(Blueprint $table){
      $table->increments('id');
      $table->string('username');
      $table->string('email');
      $table->string('role')->nullable();
      $table->string('password');
      $table->string('remember_token')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::drop('users');
  }
}
