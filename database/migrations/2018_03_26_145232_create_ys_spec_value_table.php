<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYsSpecValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ys_spec_value', function (Blueprint $table) {
        	$table->increments('id');        	 
            $table->string('name')->comment="规格名";
            $table->integer('spec_id')->comment="规格id";
            $table->integer('supplier_id')->comment="供应商id";
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ys_spec_value');
    }
}
