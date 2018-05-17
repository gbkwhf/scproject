<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsApplyReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_apply_return', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->comment="用户id";
            $table->tinyInteger('confirm_state')->comment="确认状态0未确认，1已确认";
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
        Schema::drop('ys_apply_return');
    }
}
