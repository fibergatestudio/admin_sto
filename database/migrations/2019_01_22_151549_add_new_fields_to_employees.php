<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dateTime('date_join')->nullable();
            $table->string('fio')->nullable();
            $table->string('passport')->nullable();
            $table->string('id_code')->nullable();
            $table->string('reserve_phone')->nullable();
            $table->string('hour_from')->nullable();
            $table->string('hour_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('date_join');
            $table->dropColumn('fio');
            $table->dropColumn('passport');
            $table->dropColumn('id_code');
            $table->dropColumn('reserve_phone');
            $table->dropColumn('hour_from');
            $table->dropColumn('hour_to');
        });
    }
}
