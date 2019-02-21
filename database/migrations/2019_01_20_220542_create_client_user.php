<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = 'client';
        $password = 'client';

        $data = [
            [
                'id' => 8,
                'name' => $name,
                'email' => 'zh@m.ru',
                'password' =>  Hash::make($password),
                'role' => 'client'
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
        //
    }
}
