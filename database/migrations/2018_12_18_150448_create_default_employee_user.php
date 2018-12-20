<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateDefaultEmployeeUser extends Migration
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
                'id' => '2',
                'name' => 'qwerty',
                'email'=> 'qwerty@mail.com',
                'password' => Hash::make('qwerty'),
                'role' => 'employee'
            ]
        ];

        DB::table('users')->insert($demo_values);
        
        DB::table('employees')->where('id', '=', '1')->update(['user_id' => 2]); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->where('id', '=', '2')->delete();
        DB::table('employees')->where('id', '=', '1')->update(['user_id' => 0]); 
    }
}
