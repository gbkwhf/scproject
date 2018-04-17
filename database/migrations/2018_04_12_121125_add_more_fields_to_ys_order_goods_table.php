<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToYsOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_order_goods', function (Blueprint $table) {
        	$table->bigInteger('ext_id')->comment="商品扩展表id";
        	$table->decimal('price',10,2)->comment="购买价格";        	
        	$table->string('name')->comment="商品名";
        	$table->string('image')->comment="商品图片";
        	$table->decimal('rebate_amount',10,2)->comment="返现金额";
            $table->decimal('supplier_price',10,2)->comment="供应商结算价";
            $table->string('user_comment')->comment="评价内容";
            $table->string('comment_image')->comment="评价图片";
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
        Schema::table('ys_order_goods', function (Blueprint $table) {
            $table->dropColumn("ext_id");
            $table->dropColumn("price");
            $table->dropColumn("name");
            $table->dropColumn("image");
            $table->dropColumn("rebate_amount");
            $table->dropColumn("supplier_price");
            $table->dropColumn("user_comment");
            $table->dropColumn("comment_image");
        });
    }
}
