<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReclamationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_reclamations', function (Blueprint $table) {
			$table->engine = 'MyISAM';
			$table->increments('id');
			$table->string('id_1c',36)->nullable();
			$table->integer('implementation_product_id');
			$table->foreign('implementation_product_id')->references('id')->on('b2b_implementation_products')->onDelete('cascade');
			$table->integer('quantity');
			$table->text('note');
			$table->string('ttn',255)->nullable();
			$table->enum('status',['wait', 'consideration', 'return', 'change', 'fail'])->default('wait');
			$table->integer('author');
			$table->foreign('author')->references('id')->on('wl_users')->onDelete('cascade');
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
        Schema::dropIfExists('reclamations');
    }
}
