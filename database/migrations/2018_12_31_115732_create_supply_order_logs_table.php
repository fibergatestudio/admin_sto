<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_order_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supply_order_id'); // ID заказа - foreign
            $table->foreign('supply_order_id')->references('id')->on('supply_orders');
            $table->string('log_entry_content'); // Текст вхождения
            $table->unsignedInteger('author_id'); // Автор записи в истории : ID - foreign key
            $table->foreign('author_id')->references('id')->on('users');
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
        Schema::dropIfExists('supply_order_logs');
    }
}
