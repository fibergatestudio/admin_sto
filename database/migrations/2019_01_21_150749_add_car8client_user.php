<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCar8clientUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $demo_values = [
            [
                'id' => 8,
                'general_name' => 'Acura TL',
                'owner_client_id' => '8',
                'release_year'=>'2019',
                'reg_number' => '5434342',
                'fuel_type'=>'type',
                'vin_number'=>'12345',
                'engine_capacity'=>'1',
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
