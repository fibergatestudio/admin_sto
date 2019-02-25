<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryPassagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_passages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('log_id')->lenght(10)->unsigned()->nullable();
            $table->bigInteger('time')->lenght(11)->unsigned()->nullable();
            $table->string('emp_id')->nullable();
            $table->string('internal_emp_id')->nullable();
            $table->enum('direction', ['1', '2','3'])->default('3');
            $table->text('test')->nullable();
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
        Schema::dropIfExists('delivery_passages');
    }
}
