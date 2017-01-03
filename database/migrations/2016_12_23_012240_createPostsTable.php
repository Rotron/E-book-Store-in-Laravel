<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{

    public function up()
    {
      Schema::create('posts', function(Blueprint $table){
        $table->increments('id');
        $table->string('slug')->unique();
        $table->string('title');
        $table->text('body');
        $table->timestamps();
      });
    }

    public function down()
    {
      Schema::drop('posts');
    }

}
