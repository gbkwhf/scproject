<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsSupplierCashApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_supplier_cash_apply', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('supplier_id')->comment="供应商id";
            $table->decimal('amount',10,2)->comment="金额";            
            $table->dateTime('created_at')->comment="提现时间";
            $table->tinyInteger('state')->comment="状态0申请中，1已完成";
            $table->string('pay_describe', 100)->comment="备注";
            $table->string('bank_name', 50)->nullable()->comment="开户行名称";
            $table->string('bank_address',50)->nullable()->comment="开户行地址";
            $table->string('bank_num', 50)->nullable()->comment="卡号";
            $table->string('real_name', 50)->nullable()->comment="持卡人名";
            $table->dateTime('finish_at')->comment="完成时间";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_supplier_cash_apply');
    }
}
