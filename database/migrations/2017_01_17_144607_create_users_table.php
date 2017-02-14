<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  public function up()
  {
    Schema::create('users', function(Blueprint $table){
      $table->increments('id');
      $table->string('username')->unique();
      $table->string('email')->unique();
      $table->integer('role');
      $table->string('confirmation_code')->nullable();
      $table->string('reset_token')->nullable();
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
