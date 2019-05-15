<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarWashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_wash', function (Blueprint $table) {
            $table->increments('id');
            $table->string('car_model')->nullable();
            $table->string('car_number')->nullable();
            $table->string('trailer_number')->nullable();
            $table->string('firm_name')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('wash_services')->nullable();
            $table->string('payment_sum')->nullable();
            $table->string('box_number')->nullable();
            $table->string('date')->nullable();
            $table->string('status')->nullabe();
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
        Schema::dropIfExists('car_wash');
    }
}
