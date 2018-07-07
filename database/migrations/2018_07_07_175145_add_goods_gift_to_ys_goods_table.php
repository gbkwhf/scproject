<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodsGiftToYsGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_goods', function (Blueprint $table) {

            $table->tinyInteger('goods_gift')->default(1)->comment="商品类型1普通商品，2积分兑换商品";
            $table->tinyInteger('can_back')->default(0)->comment="是否可退货0,可以退，1不可以退";
            $table->integer('use_score')->comment="可用积分";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ys_goods', function (Blueprint $table) {

            $table->dropColumn('goods_gift');
            $table->dropColumn('can_back');
            $table->dropColumn('use_score');
        });
    }
}
