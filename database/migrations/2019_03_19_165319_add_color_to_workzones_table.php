<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorToWorkzonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workzones', function (Blueprint $table) {
            $table->string('workzone_color')->nullable();
            $table->string('works_direction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workzones', function (Blueprint $table) {
            $table->dropColumn('workzone_color');
            $table->dropColumn('works_direction');
        });
    }
}
