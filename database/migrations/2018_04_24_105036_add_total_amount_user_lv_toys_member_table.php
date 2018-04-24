<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalAmountUserLvToysMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_member', function (Blueprint $table) {
            $table->decimal('total_amount',10,2)->comment="截止上月累计消费金额";
            $table->integer('user_lv')->comment="截止上月用户等级";
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
        Schema::table('ys_member', function (Blueprint $table) {
            //
            $table->dropColumn("total_amount");
            $table->dropColumn("user_lv");
        });
    }
}
