<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtToYsEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_employee', function (Blueprint $table) {
            $table->dateTime("deleted_at")->nullable();
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
        Schema::table('ys_employee', function (Blueprint $table) {
            //
            $table->dropColumn("deleted_at");
        });
    }
}
