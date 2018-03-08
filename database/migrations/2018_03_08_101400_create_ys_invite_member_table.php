<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsInviteMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {	
        Schema::create('ys_invite_member', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->comment="用户id";
            $table->bigInteger('employee_id')->comment="员工id";
            $table->Integer('agency_id')->comment="店id";
            $table->dateTime('created_time')->comment="提交时间";
            $table->dateTime('receive_time')->nullable()->comment="领取确认时间";
            $table->Integer('gift')->comment="礼品id";
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_invite_member');
    }
}
