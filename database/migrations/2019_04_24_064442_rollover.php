<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rollover extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rollovers', function (Blueprint $table) {
        $table->increments('id');
        $table->bigInteger('history_id');
        $table->bigInteger('user_id');
        $table->Integer('tenure');
        $table->string('type');
        $table->boolean('status')->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('rollovers');
    }
}
