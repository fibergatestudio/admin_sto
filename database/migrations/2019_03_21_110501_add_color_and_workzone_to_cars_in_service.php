<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorAndWorkzoneToCarsInService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars_in_service', function (Blueprint $table) {
            $table->string('car_color')->nullable();
            $table->string('workzone')->nullable();
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
            $table->dropColumn('car_color');
            $table->dropColumn('workzone');
        });
    }
}
