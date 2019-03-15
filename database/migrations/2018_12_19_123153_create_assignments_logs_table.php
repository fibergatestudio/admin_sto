<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id'); // foreign
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->unsignedInteger('author'); //foreign
            $table->foreign('author')->references('id')->on('users');
            $table->string('text');
            $table->string('type');
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
        Schema::dropIfExists('assignments_logs');
    }
}
