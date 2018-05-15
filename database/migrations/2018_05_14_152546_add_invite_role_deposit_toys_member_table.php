<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInviteRoleDepositToysMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ys_member', function (Blueprint $table) {

            $table->tinyInteger('invite_role')->default(0)->comment="邀请权限，0没有，1有";
            $table->decimal('deposit')->comment="已交押金";
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

            $table->dropColumn('invite_role');
            $table->dropColumn('deposit');
        });
    }
}
