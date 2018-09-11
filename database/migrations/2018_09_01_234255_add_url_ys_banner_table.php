<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlYsBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_banner_manage', function (Blueprint $table) {
            $table->string('url')->comment="商品链接";
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
        Schema::table('ys_banner_manage', function (Blueprint $table) {
            //
            $table->dropColumn("url");
        });
    }
}
