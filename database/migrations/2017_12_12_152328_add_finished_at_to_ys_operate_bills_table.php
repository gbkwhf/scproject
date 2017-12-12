<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinishedAtToYsOperateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_operate_bills', function (Blueprint $table) {
            $table->dateTime("finished_at")->nullable();
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
        Schema::table('ys_operate_bills', function (Blueprint $table) {
            //
            $table->dropColumn("finished_at");
        });
    }
}
