<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id'); // foreign
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedInteger('author_id'); // foreign
            $table->foreign('author_id')->references('id')->on('users');
            $table->string('text');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('employees_notes');
    }
}
