<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifications2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',500);
            $table->string('text',500)->nullable();
            $table->string('link',1000)->nullable();
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')->references('id')->on('notification_types')->onDelete('cascade');
			$table->integer('user_from_id')->nullable();
			$table->foreign('user_from_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->integer('user_to_id');
			$table->foreign('user_to_id')->references('id')->on('wl_users')->onDelete('cascade');
			$table->boolean('is_new')->default(true);
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::dropIfExists('notifications');
    }
}
