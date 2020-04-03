<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateB2bTicketMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_ticket_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->text('text');
			$table->boolean('is_new')->default(true);
			$table->integer('user_id');
			$table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->bigInteger('ticket_id')->unsigned();
			$table->foreign('ticket_id')->references('id')->on('b2b_tickets')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_ticket_messages');
    }
}
