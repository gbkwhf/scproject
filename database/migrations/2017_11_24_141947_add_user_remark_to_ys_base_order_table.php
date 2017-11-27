<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserRemarkToYsBaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_base_order', function (Blueprint $table) {
            $table->string('user_remark', 100)->nullable()->comment="用户备注";
            $table->string('manage_remark',100)->nullable()->comment="客服备注";
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
        Schema::table('ys_base_order', function (Blueprint $table) {
            //
            $table->dropColumn("user_remark");
            $table->dropColumn("manage_remark");
        });
    }
}
