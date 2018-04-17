<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtIdToCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_goods_car', function (Blueprint $table) {

             $table->integer('ext_id')->comment="商品扩展表id";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ys_goods_car', function (Blueprint $table) {

            $table->dropColumn('ext_id');
        });
    }
}
