<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::dropIfExists('b2b_chats');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::create('b2b_chats', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('subject',255);
			$table->integer('user_id');
			$table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->integer('manager_id');
			$table->foreign('manager_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->timestamps();
		});
    }
}
