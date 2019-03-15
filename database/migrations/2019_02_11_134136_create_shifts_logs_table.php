<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shift_id'); // foreign
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->unsignedInteger('employee_id'); // foreign
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->time('opened_at');
            $table->time('closed_at')->nullable();
            $table->string('text');
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
        Schema::dropIfExists('shifts_logs');
    }
}
