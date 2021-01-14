<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('discount_name', 20);
            $table->integer('level');
            $table->integer('discount_threshold');
            $table->integer('discount_gift');
            $table->timestamp('discount_create_time')->nullable();
            $table->timestamp('discount_updata_time')->nullable();
            $table->enum('discount_status', ['Y','N','D']);

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount');

    }
}
