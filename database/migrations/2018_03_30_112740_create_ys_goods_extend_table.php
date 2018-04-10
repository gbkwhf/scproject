<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsGoodsExtendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_goods_extend', function (Blueprint $table) {
        	$table->increments('id');        	 
        	$table->bigInteger('goods_id')->comment="商品id";
            $table->string('name')->comment="商品全名";
            $table->string('goods_spec')->comment="商品规格";
            $table->decimal('market_price',10,2)->comment="市场价";
            $table->decimal('price',10,2)->comment="销售价";
            $table->decimal('cost_price',10,2)->comment="成本价";
            $table->decimal('supplier_price',10,2)->comment="供应商商结算价";
            $table->decimal('rebate_amount',10,2)->comment="返利金额";
            $table->integer('num')->comment="数量";
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_goods_extend');    	
    }
}
