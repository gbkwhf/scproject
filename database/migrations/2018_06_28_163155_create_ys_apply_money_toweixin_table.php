<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsApplyMoneyToweixinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //user_id amount  state0申请中，1已通过，2，拒绝  created_at  updated_at  ,order_id, open_id
        Schema::create('ys_apply_money_toweixin', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->comment="用户id";
            $table->decimal('amount',10,2)->comment="金额";
            $table->tinyInteger('state')->comment="state0申请中，1已通过，2，拒绝";
            $table->string('order_id',200)->comment="订单号";
            $table->string('open_id',200)->comment="微信openid";
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
        Schema::drop('ys_apply_money_toweixin');
    }
}
