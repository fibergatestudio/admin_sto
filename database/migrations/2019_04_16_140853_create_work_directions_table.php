<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_directions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $values = [
            ['id' => 1, 'name' => 'Разборка-Сборка'],
            ['id' => 2, 'name' => 'Электрика'],
            ['id' => 3, 'name' => 'Слесарка'],
            ['id' => 4, 'name' => 'Рихтовка'],
            ['id' => 5, 'name' => 'Покраска'],
            ['id' => 6, 'name' => 'Детэйлинг'],
            ['id' => 7, 'name' => 'Малярка']
        ];
        
        DB::table('work_directions')->insert($values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_directions');
    }
}
