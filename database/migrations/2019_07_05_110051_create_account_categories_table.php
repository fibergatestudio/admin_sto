<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $values = [
            ['id' => 1, 'name' => 'Сервис'],
            ['id' => 2, 'name' => 'Мойка'],
            ['id' => 3, 'name' => 'Банк MD'],
            ['id' => 4, 'name' => 'Польша MD'],
            ['id' => 5, 'name' => 'Фирмы Кредиторы'],
            ['id' => 6, 'name' => 'Фирмы Дебюторы'],
            ['id' => 7, 'name' => 'Кредит'],
            ['id' => 8, 'name' => 'Аукционы'],
            ['id' => 9, 'name' => 'Инвестиции'],
            ['id' => 10, 'name' => 'Без категории'],
        ];

        DB::table('account_categories')->insert($values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_categories');
    }
}
