<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthProfitabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_profitability', function (Blueprint $table) {
            $table->increments('id');
            $table->double('rental_price', 8, 2)->nullable();
            $table->double('electricity', 8, 2)->nullable();
            $table->double('water_supply', 8, 2)->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('month_profitability');
    }
}
