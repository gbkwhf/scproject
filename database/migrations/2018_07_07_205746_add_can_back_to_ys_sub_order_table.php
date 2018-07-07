<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCanBackToYsSubOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_sub_order', function (Blueprint $table) {

            $table->tinyInteger('back_state')->default(0)->comment="是否退货，0未退，1已退";
            $table->tinyInteger('can_back')->default(0)->comment="是否可退货0,可以退，1不可以退";
            $table->integer('use_score')->comment="已用积分";
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ys_sub_order', function (Blueprint $table) {

            $table->dropColumn('back_state');
            $table->dropColumn('can_back');
            $table->dropColumn('use_score');
        });
    }
}
