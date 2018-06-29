<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsBalanaceBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //amount  created_at  updated_at  type(1提现，2积分兑换) desc备注
        Schema::create('ys_balance_bill', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->comment="用户id";
            $table->decimal('amount',10,2)->comment="金额";
            $table->tinyInteger('type')->comment="类型1提现，2积分兑换";
            $table->string('desc',200)->comment="备注";
            $table->dateTime('created_at')->comment="创建时间";
            $table->dateTime('updated_at')->comment="修改时间";


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_balance_bill');
    }
}
