<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateCarsInServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars_in_service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('general_name');
            $table->unsignedInteger('owner_client_id');
            $table->foreign('owner_client_id')->references('id')->on('clients');
            $table->timestamps();
        });

        $demo_values = [
            [
                'id' => 1,
                'general_name' => 'Тестовая машина',
                'owner_client_id' => '1'
            ]
        ];

        DB::table('cars_in_service')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars_in_service');
    }
}
