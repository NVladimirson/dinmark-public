<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_company_prices', function (Blueprint $table) {
			$table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->string('name',255);
            $table->double('koef');
			$table->integer('company_id');
			$table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('b2b_company_prices');
    }
}
