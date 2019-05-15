<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarWashCompleteWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_wash_complete_work', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assignment_id')->nullable();
            $table->string('date')->nullable();
            $table->string('brand')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('amount')->nullable();
            $table->string('price')->nullable();
            $table->string('sum')->nullable();
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
        Schema::dropIfExists('car_wash_complete_work');
    }
}
