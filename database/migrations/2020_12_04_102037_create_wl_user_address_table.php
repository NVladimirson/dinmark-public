<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWlUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wl_user_address', function (Blueprint $table) {
            $table->bigIncrements('id')->primary;
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
            $table->integer('shipping_id');
            $table->text('shipping_info');
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
        Schema::dropIfExists('wl_user_adress');
    }
}
