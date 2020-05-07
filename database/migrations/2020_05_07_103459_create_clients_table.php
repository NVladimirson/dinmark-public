<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_clients', function (Blueprint $table) {
			$table->engine = 'MyISAM';
			$table->increments('id');
			$table->text('name');
			$table->text('company_name')->nullable();
			$table->text('company_edrpo')->nullable();
			$table->text('email')->nullable();
			$table->text('phone')->nullable();
			$table->text('address')->nullable();
			$table->integer('company_id');
			$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::dropIfExists('clients');
    }
}
