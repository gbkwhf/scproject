<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsAfterExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_after_exchange', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->comment="提问者id";
            $table->string('user_problem')->comment="提出的问题";
            $table->string('merchant_reply')->comment="商家回复";
            $table->dateTime('created_at')->comment="创建时间";

        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_after_exchange');
    }
}
