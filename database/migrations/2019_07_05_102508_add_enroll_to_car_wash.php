<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollToCarWash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_wash', function (Blueprint $table) {
            $table->string('enrolled')->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_wash', function (Blueprint $table) {
            $table->dropColumn('enrolled');
        });
    }
}
