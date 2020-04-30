<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImplementationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_implementation_products', function (Blueprint $table) {
			$table->engine = 'MyISAM';
			$table->increments('id');
			$table->string('id_1c',36)->nullable();
			$table->integer('order_product_id');
			$table->foreign('order_product_id')->references('id')->on('s_cart_products')->onDelete('cascade');
			$table->integer('quantity');
			$table->float('total');
			$table->integer('date_add');
			$table->integer('date_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('implementation_products');
    }
}
