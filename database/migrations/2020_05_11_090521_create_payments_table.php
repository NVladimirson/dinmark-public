<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_payments', function (Blueprint $table) {
			$table->engine = 'MyISAM';
			$table->increments('id');
			$table->string('id_1c',36)->nullable();
			$table->text('public_number')->nullable();
			$table->integer('cart_id');
			$table->foreign('cart_id')->references('id')->on('s_carts')->onDelete('cascade');
			$table->double('payed',8,2)->default(0);
			$table->integer('date_add');
			$table->integer('date_edit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
