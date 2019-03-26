<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMonthProfitabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('month_profitability', function (Blueprint $table) {
            $table->double('gas', 8, 2)->nullable();
            $table->double('cleaning', 8, 2)->nullable();
            $table->double('garbage_removal', 8, 2)->nullable();
            $table->double('other_expenses', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('month_profitability', function (Blueprint $table) {
            $table->dropColumn('gas');
            $table->dropColumn('cleaning');
            $table->dropColumn('garbage_removal');
            $table->dropColumn('other_expenses');
        });
    }
}
