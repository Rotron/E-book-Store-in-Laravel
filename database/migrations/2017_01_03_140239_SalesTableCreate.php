<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalesTableCreate extends Migration
{
  public function up()
  {
    Schema::create('sales', function(Blueprint $table){
      $table->increments('id');
      $table->integer('product_id');
      $table->string('buyers_name');
      $table->string('buyers_email');
      $table->string('transaction_id');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::drop('sales');
  }
}
