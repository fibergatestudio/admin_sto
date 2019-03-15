<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsNotesLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars_notes_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('note_id');
            $table->foreign('note_id')->references('id')->on('cars_notes')->onDelete('cascade');
            $table->unsignedInteger('car_id'); // foreign
            $table->foreign('car_id')->references('id')->on('cars_in_service')->onDelete('cascade');
            $table->unsignedInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('cars_notes_logs');
    }
}
