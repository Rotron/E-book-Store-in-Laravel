<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
  public function up()
  {
   Schema::table('transactions', function (Blueprint $table) {
     $table->increments('id');
     $table->integer('listing_id')->references('id')->on('listings');
     $table->string('transaction_id');
     $table->string('first_name');
     $table->string('last_name');
     $table->string('email');
     $table->integer('sold');
   });
  }

  public function down()
  {
    Schema:drop('transactions');
  }
}
