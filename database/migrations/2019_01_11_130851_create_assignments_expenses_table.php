<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount'); /* Кол-во денег, которые вышли */
            $table->unsignedInteger('assignment_id'); /* Наряд, по которому зашли средства - Foreign ~ assignments.id */
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->string('basis'); /* Основание для расхода денег (реквизиты документа или описание, за что они были приняты в кассу) */
            $table->string('description'); /* Поле для описания / комментария */
            $table->timestamps(); // Стандартые created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments_expenses');
    }
}
