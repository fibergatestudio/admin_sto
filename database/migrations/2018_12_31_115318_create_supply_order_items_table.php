<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supply_order_id'); // ID заказа - foreign
            $table->foreign('supply_order_id')->references('id')->on('supply_orders');
            $table->string('item'); // Наименование позиции
            $table->string('description')->nullable(); // Описание позиции
            $table->string('comment')->nullable(); // Примечание к позиции
            $table->smallInteger('number'); // Количество заказанного товара
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
        Schema::dropIfExists('supply_order_items');
    }
}
