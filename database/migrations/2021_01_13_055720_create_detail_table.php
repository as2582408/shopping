<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail', function (Blueprint $table) {
            //
            $table->increments('detail_id');
            $table->integer('user_id');
            $table->integer('detail_discount_id');
            $table->integer('detail_totail_price');
            $table->string('detail_description');
            $table->enum('detail_status', ['0','1','2']);
            $table->enum('detail_shipment', ['1','2','3']);
            $table->timestamp('detail_updata_time')->nullable();
            $table->timestamp('detail_create_time')->nullable();
            $table->timestamp('detail_end_time')->nullable();
            $table->string('user_phone', 10);
            $table->string('user_address');
            $table->integer('detail_shopping_point');
            $table->integer('detail_gift_money');
            $table->string('detail_remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail');

    }
}
