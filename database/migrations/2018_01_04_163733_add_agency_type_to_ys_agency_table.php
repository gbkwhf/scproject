<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgencyTypeToYsAgencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_agency', function (Blueprint $table) {
            $table->tinyInteger('agency_type')->comment="经销商类型1普通经销商，2非返现经销商";
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
        Schema::table('ys_agency', function (Blueprint $table) {
            //
            $table->dropColumn("agency_type");
        });
    }
}
