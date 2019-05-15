<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarWashAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_wash_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wash_id')->nullable();
            $table->string('client_id')->nullable();
            $table->string('print_settings_id')->nullable();
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
        Schema::dropIfExists('car_wash_assignments');
    }
}
