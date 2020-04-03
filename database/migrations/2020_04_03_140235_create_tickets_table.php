<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('subject',255);
			$table->enum('status',['open','close']);
			$table->integer('user_id');
			$table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->integer('manager_id');
			$table->foreign('manager_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->integer('created_at');
			$table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
