<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    public function up()
    {
      Schema::create('listings', function (Blueprint $table) {
        $table->increments('id');
        $table->string('listing_name')->unique();
        $table->string('listing_name_slug')->unique();
        $table->string('type');
        $table->text('listing_description');
        $table->integer('listing_price');
        $table->string('listing_pdf');
        $table->string('listing_image');
        $table->timestamps();
      });
    }

   public function down()
   {
      Schema::drop('listings');
   }
}
