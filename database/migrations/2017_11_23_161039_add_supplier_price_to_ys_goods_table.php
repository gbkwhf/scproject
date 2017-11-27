<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierPriceToYsGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_goods', function (Blueprint $table) {
            $table->decimal('supplier_price',10,2)->comment="供应商结算价";
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
        Schema::table('ys_goods', function (Blueprint $table) {
            //
            $table->dropColumn("supplier_price");
        });
    }
}
