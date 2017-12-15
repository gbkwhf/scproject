<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsExpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_express', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->comment="快递公司名";
            $table->string('e_name',100)->comment="快递公司查询名";
            $table->Integer('sort')->comment="排序";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_express');
    }
}
