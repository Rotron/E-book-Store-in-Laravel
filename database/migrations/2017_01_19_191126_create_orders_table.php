<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
  public function up()
  {
   Schema::create('orders', function (Blueprint $table) {
     $table->increments('id');

     $table->integer('user_id');
     $table->integer('listing_id');

     $table->string('txn_id');
     $table->string('first_name');
     $table->string('last_name');
     $table->string('payer_email');
     $table->timestamps();
   });
  }

  public function down()
  {
    Schema::drop('orders');
  }
}
