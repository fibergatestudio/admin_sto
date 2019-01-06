<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Название
            $table->string('description')->nullable();// Описание
            $table->unsignedInteger('assignment_id'); // Привязка к наряду, foreign
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->unsignedInteger('workzone_id'); // Рабочая зона, foreign
            $table->foreign('workzone_id')->references('id')->on('workzones');
            $table->unsignedInteger('responsible_employee_id'); // Ответственный работник, foreign
            $table->foreign('responsible_employee_id')->references('id')->on('users');
            $table->date('date_of_creation');
			$table->date('date_of_completion')->nullable();
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
        Schema::dropIfExists('sub_assignments');
    }
}
