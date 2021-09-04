<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('order_product', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('quantity')->unsigned();
        //     $table->timestamps();
        // });

        // Schema::table('order_product', function (Blueprint $table) {
        //     $table->integer('order_id')->unsigned()->nullable();
        //     $table->foreign('order_id')->references('id')
        //           ->on('orders')->onUpdate('cascade')->onDelete('set null');

        //     $table->integer('product_id')->unsigned()->nullable();
        //     $table->foreign('product_id')->references('id')
        //         ->on('products')->onUpdate('cascade')->onDelete('set null');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('order_product');
    }
}
