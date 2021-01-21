<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatPointLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_log', function (Blueprint $table) {

            $table->increments('log_id');
            $table->integer('log_user_id');
            $table->integer('log_detail');
            $table->integer('log_change_gold');
            $table->integer('log_new_gold');
            $table->enum('log_type', ['1','2', '3','4','5']);
            $table->timestamp('log_time')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_log');

    }
}
