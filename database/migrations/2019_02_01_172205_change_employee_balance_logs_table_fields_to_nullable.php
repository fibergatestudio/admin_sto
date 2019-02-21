<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmployeeBalanceLogsTableFieldsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_balance_logs', function (Blueprint $table) {
            $table->string('reason')->nullable()->change();
            $table->string('action')->nullable()->change();
            $table->string('source')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_balance_logs', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('action');
            $table->dropColumn('source');
        });
    }
}
