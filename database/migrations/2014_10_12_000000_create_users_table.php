<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active')->default('0');
            $table->string('verify')->default('');
            $table->string('role')->default('cus');
            $table->string('number')->nullable();
            $table->string('mentor')->default('');
            $table->string('referal')->nullable();
            $table->string('mode_id')->nullable();
            $table->string('identity')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('acc_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('addr')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('id_change')->default('0');
            //$table->string('user_id');
            $table->string('reason')->default('Unverified Email Address');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
