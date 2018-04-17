<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllRebateToYsBaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_base_order', function (Blueprint $table) {
            $table->decimal('all_rebate',10,2)->comment="总返利金额";
            $table->decimal('shipping_price',10,2)->comment="总运费";
            $table->dropColumn("rebate_num");
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
        Schema::table('ys_base_order', function (Blueprint $table) {
            //
            $table->dropColumn("all_rebate");
            $table->dropColumn("shipping_price");
            $table->decimal('rebate_num')->comment="返利份数";
        });
    }
}
