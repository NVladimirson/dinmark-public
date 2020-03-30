<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToDataChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b2b_user_data_change_requests', function (Blueprint $table) {
            $table->enum('status',['await','success','rejected'])->default('await');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b2b_user_data_change_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
