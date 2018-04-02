<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecToYsGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_base_order', function (Blueprint $table) {
            $table->string('spec_name', 300)->nullable()->comment="规格名";
            $table->string('spec_value',300)->nullable()->comment="规格值";
            $table->integer('store_class')->comment="店内分类";
            $table->decimal('rebate_amount',10,2)->comment="返利金额";
            $table->decimal('shipping_price',10,2)->comment="运费";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ys_base_order', function (Blueprint $table) {
            //
            $table->dropColumn("spec_name");
            $table->dropColumn("spec_value");
            $table->dropColumn("store_class");
            $table->dropColumn("store_class");
            $table->dropColumn("rebate_amount");
            $table->dropColumn("shipping_price");
        });
    }
}
