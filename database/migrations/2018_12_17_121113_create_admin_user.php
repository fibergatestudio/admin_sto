<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $default_admin_name = 'admin';
        $default_admin_password = Hash::make('admin');
        $default_admin_mail = 'mail@example.com';
        
        $data = [
            [
                'id' => 1,
                'name' => $default_admin_name,
                'email' => $default_admin_mail,
                'password' => $default_admin_password,
                'role' => 'admin'
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
        DB::delete('DELETE FROM users WHERE id = 1');
    }
}
