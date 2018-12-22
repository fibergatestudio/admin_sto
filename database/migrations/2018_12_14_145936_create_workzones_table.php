<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateWorkzonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workzones', function (Blueprint $table) {
            $table->increments('id');
			$table->string('general_name');
			$table->string('description')->nullable();
            $table->timestamps();
        });

        $demo_values = [
            [
                'id' => 1,
                'general_name' => 'Тестовый рабочий пост',
                'description' => 'Тестовое описание'
            ]
        ];

        DB::table('workzones')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workzones');
    }
}
