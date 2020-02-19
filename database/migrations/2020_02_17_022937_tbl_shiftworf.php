<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblShiftworf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shiftworks', function (Blueprint $table) {
            $table->bigIncrements('id');
           
            $table->string('name_shift');
            $table->string('name_admin');
            $table->unsignedInteger('price_box');
            $table->unsignedInteger('total_price');
            $table->dateTime('created_at');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shiftworks');
    }
}
