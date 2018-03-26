<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsBannerManageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_banner_manage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('img_url')->comment="图片地址";
            $table->integer('sort')->default(255)->comment="排序";
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_banner_manage');
    	
    }
}
