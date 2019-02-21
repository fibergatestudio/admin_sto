<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToCarsInService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars_in_service', function (Blueprint $table) {
            $table->mediumInteger('release_year')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('vin_number')->nullable();
            $table->mediumInteger('engine_capacity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars_in_service', function (Blueprint $table) {
            $table->dropColumn('release_year');
            $table->dropColumn('reg_number');
            $table->dropColumn('fuel_type');
            $table->dropColumn('vin_number');
            $table->dropColumn('engine_capacity');
        });
    }
}
