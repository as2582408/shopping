<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_name', 30);
            $table->string('product_img');
            $table->integer('product_price');
            $table->integer('product_amount');
            $table->timestamp('product_create_time')->nullable();
            $table->timestamp('product_updata_time')->nullable();
            $table->enum('product_status', ['Y','N','D']);
            $table->string('product_description');
            $table->string('product_category');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        //
    }
}
