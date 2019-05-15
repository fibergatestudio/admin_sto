<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarWashPrintSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_wash_print_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assignment_number')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('doc_for')->nullable();
            $table->string('doc_date')->nullable();
            $table->string('VAT')->nullable();
            $table->string('show_wash_date')->nullable();
            $table->string('note')->nullable();
            $table->string('show_logo')->nullable();
            $table->string('invoice')->nullable();
            $table->string('invoice_status')->nullable();
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
        Schema::dropIfExists('car_wash_print_settings');
    }
}
