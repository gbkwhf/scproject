<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodsTypeToysGoodsClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_goods_class', function (Blueprint $table) {
            $table->integer('goods_type')->comment="商品类型";
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ys_goods_class', function (Blueprint $table) {
            //
            $table->dropColumn("goods_type");
        });
    }
}
