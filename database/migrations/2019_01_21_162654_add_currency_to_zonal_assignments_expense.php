<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyToZonalAssignmentsExpense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zonal_assignments_expense', function (Blueprint $table) {
            $table->string('zonal_currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zonal_assignments_expense', function (Blueprint $table) {
            $table->dropColumn('zonal_currency');
        });
    }
}
