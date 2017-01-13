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
     $table->integer('listing_id')->unsigned();
     $table->foreign('listing_id')->references('id')->on('listings');
     $table->string('txn_id');
     $table->string('first_name');
     $table->string('last_name');
     $table->string('payer_email');
     $table->integer('total_sold');
   });
  }

  public function down()
  {
    Schema:drop('transactions');
  }
}
