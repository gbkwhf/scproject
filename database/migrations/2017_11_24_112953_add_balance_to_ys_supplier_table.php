<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBalanceToYsSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_supplier', function (Blueprint $table) {
            $table->decimal('balance',10,2)->default(0)->comment="供应商余额";
            $table->string('bank_name', 50)->nullable()->comment="开户行名称";
            $table->string('bank_address',50)->nullable()->comment="开户行地址";
            $table->string('bank_num', 50)->nullable()->comment="卡号";
            $table->string('real_name', 50)->nullable()->comment="持卡人名";
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
            $table->dropColumn("balance");
            $table->dropColumn("bank_name");
            $table->dropColumn("bank_address");
            $table->dropColumn("bank_num");
            $table->dropColumn("real_name");
        });
    }
}
