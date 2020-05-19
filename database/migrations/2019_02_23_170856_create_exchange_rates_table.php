<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->double('usd')->nullable();
            $table->double('eur')->nullable();
            $table->timestamps();
        });

        $demo_values = [
            [
                'usd' => 0.056,
                'eur'=> 0.052
            ]
        ];

        DB::table('exchange_rates')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
