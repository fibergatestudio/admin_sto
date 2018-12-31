<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('creator_id'); // создатель заказа - foreign 
            $table->foreign('creator_id')->references('id')->on('users');
            $table->unsignedInteger('responsible_supply_officer_id')->nullable(); // ответственное лицо, которое выполняет заказ - foreign
            $table->foreign('responsible_supply_officer_id')->references('id')->on('users');
            $table->date('date_of_completion')->nullable();
            $table->string('status')->default('active')->nullable();
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
        Schema::dropIfExists('supply_orders');
    }
}
