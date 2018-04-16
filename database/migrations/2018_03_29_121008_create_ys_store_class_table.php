<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsStoreClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_store_class', function (Blueprint $table) {
        	$table->increments('id');        	 
            $table->string('name')->comment="分类名";
            $table->integer('first_id')->comment="一级分类id，0代表一级分类";
            $table->integer('sort')->default(255)->comment="排序";
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_store_class');
    }
}
