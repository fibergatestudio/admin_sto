<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateEmployeeBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->float('balance');
            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        $demo_data = [
            [
                'id' => 1,
                'balance' => 500.00,
                'employee_id' => 1
            ]
        ];

        DB::table('employee_balances')->insert($demo_data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_balances');
    }
}
