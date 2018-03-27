<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsGoodsTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_goods_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment="类型名";
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
        Schema::drop('ys_goods_type');
    }
}
