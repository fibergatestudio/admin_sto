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
                'general_name' => '8 машина',
                'owner_client_id' => '8'
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
