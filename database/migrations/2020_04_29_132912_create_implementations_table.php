<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImplementationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_implementations', function (Blueprint $table) {
			$table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('id_1c',36)->nullable();
            $table->text('public_number')->nullable();
			$table->integer('sender_id')->default(0);
			$table->foreign('sender_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->integer('customer_id');
			$table->string('ttn',255)->nullable();
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
        Schema::dropIfExists('implementations');
    }
}
