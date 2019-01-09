<?php

/* Миграция создаёт тестовый аккаунт снабженца */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/* Используем фасады DB и Hash */
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddTestSupplyOfficerAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            [
                'id' => 3,
                'name' => 'supply',
                'email' => 'supply@example.com',
                'password' => Hash::make('supply'),
                'role' => 'supply_officer',
                'general_name' => 'Тестовый снабженец'
            ]
        ];

        DB::table('users')
            ->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')
            ->where('id', 3)
            ->delete();
    }
}
