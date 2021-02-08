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
            $table->string('email', 40);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('point');
            $table->integer('accumulation_point');
            $table->tinyInteger('level');
            $table->string('phone', 10);
            $table->string('address');
            $table->timestamp('login_time')->nullable();
            $table->enum('status', ['Y','N','D']);
            $table->enum('admin', ['N','Y']);
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
