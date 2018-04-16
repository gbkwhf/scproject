<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldToYsSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_supplier', function (Blueprint $table) {
            $table->string('logo')->comment="门店图";
            $table->integer('class_id')->comment="分类id";
            $table->decimal('free_shipping',10,2)->comment="包邮金额";
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
        Schema::table('ys_supplier', function (Blueprint $table) {
            //
            $table->dropColumn("logo");
            $table->dropColumn("class_id");
            $table->dropColumn("free_shipping");
        });
    }
}
