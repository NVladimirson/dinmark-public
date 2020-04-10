<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_like_groups', function (Blueprint $table) {
			$table->engine = 'MyISAM';
            $table->increments('id');
			$table->text('name');
			$table->boolean('is_main')->default(true);
			$table->integer('user_id');
			$table->foreign('user_id')->references('id')->on('wl_users')->onDelete('cascade');
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
        Schema::dropIfExists('s_like_groups');
    }
}
