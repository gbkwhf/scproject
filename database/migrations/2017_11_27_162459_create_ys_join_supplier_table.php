<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsJoinSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_join_supplier', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->comment="用户id";         
            $table->dateTime('created_at')->comment="提交时间";
            $table->tinyInteger('state')->comment="状态0未处理，1已处理";
            $table->string('name',100)->comment="联系人";
            $table->string('mobile', 50)->comment="联系电话";
            $table->string('company_name',100)->nullable()->comment="公司名";
            $table->string('goods_name',100)->comment="商品名";
            $table->string('goods_descript', 200)->nullable()->comment="商品描述";
            $table->string('img_1',200)->nullable()->comment="图片1";
            $table->string('img_2',200)->nullable()->comment="图片2";
            $table->string('img_3',200)->nullable()->comment="图片3";            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_join_supplier');
    }
}
