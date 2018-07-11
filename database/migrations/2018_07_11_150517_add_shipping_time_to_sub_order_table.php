<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingTimeToSubOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_sub_order', function (Blueprint $table) {

            $table->dateTime('shipping_time')->default(null)->comment="发货时间";
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

            $table->dropColumn('shipping_time');
        });
    }
}
