<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonalAssignmentsIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zonal_assignments_income', function (Blueprint $table) {
            /* Зональный заход денег */
            $table->increments('id');
            $table->float('zonal_amount'); /* Кол-во денег, которое зашло */
            $table->unsignedInteger('sub_assignment_id'); /* Зональный наряд, по которому зашли средства - Foreign ~ assignments.id */
            $table->foreign('sub_assignment_id')->references('id')->on('sub_assignments');
            $table->string('zonal_basis'); /* Основание для захода денег (реквизиты документа или описание, за что они были приняты в кассу) */
            $table->string('zonal_description'); /* Поле для описания / комментария */
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
        Schema::dropIfExists('zonal_assignments_income');
    }
}
