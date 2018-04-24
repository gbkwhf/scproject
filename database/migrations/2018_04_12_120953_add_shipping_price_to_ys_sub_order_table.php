<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingPriceToYsSubOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_sub_order', function (Blueprint $table) {
            $table->decimal('shipping_price',10,2)->comment="总运费";
            $table->tinyInteger('receive_state')->default(0)->comment="是否确认收货，0未确认，1已确认";
            $table->decimal('all_profit',10,2)->comment="总利润";
            $table->decimal('all_rebate',10,2)->comment="总返利金额";
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
        Schema::table('ys_sub_order', function (Blueprint $table) {
            $table->dropColumn("shipping_price");
            $table->dropColumn("receive_state");
            $table->dropColumn("all_profit");
            $table->dropColumn("all_rebate");
        });
    }
}
