<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('car_id'); // foreign
            $table->foreign('car_id')->references('id')->on('cars_in_service');
            $table->unsignedInteger('author_id'); // foreign
            $table->foreign('author_id')->references('id')->on('users');
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
        Schema::dropIfExists('cars_logs');
    }
}
