<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_detail', function (Blueprint $table) {
            //
            $table->integer('detail_id');
            $table->integer('user_id');
            $table->string('return_itme_id');
            $table->timestamp('return_create_time')->nullable();
            $table->timestamp('return_updata_time')->nullable();
            $table->enum('return_status', ['0','1','2']);
            $table->string('return_reply');
            $table->string('return_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_detail');

    }
}
