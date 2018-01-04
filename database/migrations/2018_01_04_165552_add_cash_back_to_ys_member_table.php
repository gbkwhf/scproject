<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashBackToYsMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::table('ys_member', function (Blueprint $table) {
                $table->tinyInteger('cash_back')->comment="会员类型1返现，0不返现";
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
            $table->dropColumn("cash_back");
        });
    }
}
