<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProductArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_company_product_articles', function (Blueprint $table) {
			$table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->text('article');
            $table->integer('holding_id');
			$table->integer('product_id');
			$table->foreign('product_id')->references('id')->on('s_shopshowcase_products');
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
        Schema::dropIfExists('b2b_company_product_articles');
    }
}
