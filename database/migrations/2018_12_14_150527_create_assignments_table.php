<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('responsible_employee_id'); // foreign
            $table->foreign('responsible_employee_id')->references('id')->on('employees');
            $table->unsignedInteger('car_id'); // foreign
            $table->foreign('car_id')->references('id')->on('cars_in_service');
            $table->unsignedInteger('workzone_id'); //foreign
            $table->foreign('workzone_id')->references('id')->on('workzones');
			$table->string('description')->nullable();
			$table->date('date_of_creation');
			$table->date('date_of_completion')->nullable();
			$table->string('status');
            $table->string('confirmed');
            $table->timestamps();
        });

        /* Демонстрационные данные  */
        $demo_values = [
            [
                'id' => 1,
                'responsible_employee_id' => 1,
                'car_id' => 1,
                'workzone_id' => 1,
                'description' => 'Описание тестового наряда',
                'date_of_creation' => '2018-12-17',
                'status' => 'active',
                'confirmed' => 'unconfirmed',

            ]
        ];

        DB::table('assignments')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
