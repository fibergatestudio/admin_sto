<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id'); // foreign
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedInteger('author_id'); // foreign
            $table->foreign('author_id')->references('id')->on('users');
            $table->string('text');
            $table->string('type');
            $table->timestamps();
        });

        $demo_values = [
            ['id' => 1, 'employee_id' => 1, 'author_id' => 1, 'text' => 'Тестовая запись лога по тестовому сотруднику', 'type' => 'тип']
        ];

        DB::table('employees_logs')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_logs');
    }
}
