<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->text('text');
			$table->boolean('is_new')->default(true);
			$table->integer('user_id');
			$table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->bigInteger('chat_id')->unsigned();
			$table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b2b_chat_messages');
    }
}
