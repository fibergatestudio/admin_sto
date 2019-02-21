<?php

/* Список работ, выполненных в рамках наряда (основного, не зонального) */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentJobsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_jobs_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_performed'); /* Название работы */
            $table->string('description')->nullable();/* Описание работы */
            $table->unsignedInteger('assignment_id'); /* ID наряда - Foreign */
            $table->foreign('assignment_id')->references('id')->on('assignments');
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
        Schema::dropIfExists('assignment_jobs_list');
    }
}
