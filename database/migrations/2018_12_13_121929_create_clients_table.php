<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('general_name');            
            $table->string('fio');
            $table->string('organization')->nullable();
            $table->string('phone')->nullable();
            $table->string('balance')->nullable();
            $table->string('discount')->nullable();
            $table->timestamps();
        });

        $demo_values = [
            [
                'id' => 1,
                'general_name' => 'Клиент',
                'surname' => 'Тестовый',
                'fio' => 'Клиент Тестовый',
                'phone' => '7777777'
            ],
            [
                'id' => 8,
                'general_name' => 'Клиент',
                'surname' => 'Новый',
                'fio'=>'Клиент Новый',
                'organization' => 'Organization',
                'phone'=>'0909900099',
                'balance'=>'500',
                'discount'=>'10',
            ]
        ];

        DB::table('clients')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
