<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatDetailItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_item', function (Blueprint $table) {
            //
            $table->increments('item_id');
            $table->integer('item_detail_id');
            $table->string('product_name');
            $table->integer('product_price');
            $table->integer('product_amount');
            $table->integer('product_retrun_amount');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_item');

    }
}
