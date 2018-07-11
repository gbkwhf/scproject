<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsReturnOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_return_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('base_order_id',200)->comment="主订单号";
            $table->bigInteger('user_id')->comment="用户id";
            $table->tinyInteger('state')->comment="状态0申请中，1已通过，2，拒绝";
            $table->decimal('amount')->comment="金额";
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
        Schema::drop('ys_return_order');
    }
}
