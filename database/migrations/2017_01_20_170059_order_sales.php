<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderSales extends Migration
{
  public function up()
  {
    Schema::create('order_sales', function (Blueprint $table) {
      $table->increments('id');

      $table->integer('listing_id');
      $table->integer('order_id');

      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::drop('order_sales');
  }
}
