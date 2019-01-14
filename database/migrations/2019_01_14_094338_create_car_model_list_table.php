<?php

/*
* Таблица со списком возможных названий машин 
* Используется, чтобы стандартизировать запись моделей и брендов машин
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarModelListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_model_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('general_name'); /* Общее название. Пример: Audi A1 */
            $table->string('brand'); /* Название производителя-бренда. Пример: Audi */
            $table->string('model'); /* Название модели. Пример: A1 */
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
        Schema::dropIfExists('car_model_list');
    }
}
