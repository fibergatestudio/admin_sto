<?php

/*
* Таблица со списком возможных названий машин 
* Используется, чтобы стандартизировать запись моделей и брендов машин
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        /* Импортируем данные*/
        $file_content = Storage::get('cars_csv.csv');
		$id = 1;
		
		$lines = explode(PHP_EOL, $file_content);
		foreach($lines as $line){
			
			$test = explode(';', $line);
			
			

			if(!isset($test[1])){
				// Последняя строка, не делаем ничего
			} else {
				
				$brand = $test[0]; // Задачём бренд
				$model = $test[1];// Задаём модель
				$general_name = $brand.' '.$model; // Задаём general_name
				
				// Формируем массив данных на внесение
				$data_array = [
					[
						'id' => $id,
						'general_name' => $general_name,
						'brand' => $brand,
						'model' => $model
					]
				];
				

				// Вносим данные
				DB::table('car_model_list')->insert($data_array);
				$id++; // Увеличиваем ID на 1
					
			} //endif
			
		} // end foreach
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
