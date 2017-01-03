<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{

    public function up()
    {
      Schema::create('products', function(Blueprint $table){
        $table->increments('id');
        $table->string('product_name');
        $table->integer('price');
        $table->text('product_description');
        $table->timestamps();
      });
    }

    public function down()
    {
      Schema::drop('products');
    }
}
