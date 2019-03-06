<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAssignmentsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_assignments_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id'); // Привязка к наряду, foreign
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->unsignedInteger('workzone_id'); // Рабочая зона, foreign
            $table->foreign('workzone_id')->references('id')->on('workzones');
            $table->unsignedInteger('responsible_employee_id'); // Ответственный работник, foreign
            $table->foreign('responsible_employee_id')->references('id')->on('users');
            $table->unsignedInteger('car_id'); // foreign
            $table->foreign('car_id')->references('id')->on('cars_in_service');
            $table->unsignedInteger('author'); //foreign
            $table->foreign('author')->references('id')->on('users');
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
        Schema::dropIfExists('sub_assignments_logs');
    }
}
