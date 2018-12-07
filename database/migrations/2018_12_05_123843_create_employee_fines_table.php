<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateEmployeeFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_fines', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount');
            $table->date('date');
            $table->string('status');
            $table->unsignedInteger('employee_id');
            $table->string('reason');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestamps();
        });

        $demo_values = [
            [
                'id' => 1,
                'amount' => 100,
                'date' => '2018-12-05',
                'status' => 'pending',
                'employee_id' => 1,
                'reason' => 'Основание для применения тестового штрафа'
            ]
        ];

        DB::table('employee_fines')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_fines');
    }
}
