<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDataChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_user_data_change_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type',['email','phone']);
            $table->text('value');
			$table->integer('user_id')->nullable();
			$table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_user_data_change_requests');
    }
}
