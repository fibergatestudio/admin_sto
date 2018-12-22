<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeBalanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_balance_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount');
            $table->string('reason'); // Формальное основание для действия
            $table->string('action'); // Действие относительно балланса: + (deposit) или - (withdrawal)
            $table->string('source'); // Источник действия: manual (добавлено вручную), auto (добавлено системой)
            $table->date('date');
            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
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
        Schema::dropIfExists('employee_balance_logs');
    }
}
