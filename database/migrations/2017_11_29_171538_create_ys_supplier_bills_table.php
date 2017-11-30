<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsSupplierBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_supplier_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('supplier_id')->comment="供应商id";
            $table->decimal('amount',10,2)->comment="金额";            
            $table->dateTime('created_at')->comment="时间";           
            $table->string('pay_describe', 100)->comment="备注";
            $table->tinyInteger('type')->comment="类型1销售收入，1提现扣除";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_supplier_bills');
    }
}
