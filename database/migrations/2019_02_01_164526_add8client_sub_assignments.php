<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add8clientSubAssignments extends Migration
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
                'id' => 1,
                'name'=>'Зональный наряд 1',
                'description' => 'Осмотр свечей',
                'assignment_id'=>1,
                'workzone_id'=>1,
                'responsible_employee_id'=>1,
                'date_of_creation'=>'2018-12-16',
                'date_of_completion'=>'2018-12-18'
            ]
        ];

        DB::table('sub_assignments')->insert($demo_values);
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
