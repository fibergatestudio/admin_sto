<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrgencyToSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Проблема, нужна проверка */
        Schema::table('supply_orders', function (Blueprint $table) {
            $table->tinyInteger('urgency')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Проблема, нужна проверка */
        Schema::table('supply_orders', function (Blueprint $table) {
            $table->dropColumn('urgency');
        });
    }
}
