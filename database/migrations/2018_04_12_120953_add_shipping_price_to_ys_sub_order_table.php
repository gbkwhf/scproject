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
        });
    }
}
