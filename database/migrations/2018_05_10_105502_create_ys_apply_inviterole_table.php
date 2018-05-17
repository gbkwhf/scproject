<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsApplyInviteroleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //order_id,user_id,mobile,name,user_lv,price,state（0未支付，1已支付）,created_at,updated_at,confirm_state,
        Schema::create('ys_apply_inviterole', function (Blueprint $table) {
            $table->string('order_id');
            $table->bigInteger('user_id')->comment="用户id";
            $table->string('mobile')->comment="联系电话";
            $table->string('name')->comment="联系人";
            $table->integer('user_lv')->comment="用户等级";
            $table->decimal('price',10,2)->comment="金额";
            $table->tinyInteger('state')->comment="状态0未支付，1已支付";
            $table->tinyInteger('confirm_state')->comment="确认状态0未确认，1已确认";
            $table->dateTime('created_at')->comment="创建时间";
            $table->dateTime('updated_at')->comment="修改时间";
            $table->primary('order_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_apply_inviterole');
    }
}
