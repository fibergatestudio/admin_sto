<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = 'master';
        $password = 'master';

        $data = [
            [
                'id' => 9,
                'name' => $name,
                'email' => 'zh@m.com',
                'password' =>  Hash::make($password),
                'role' => 'master'
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
