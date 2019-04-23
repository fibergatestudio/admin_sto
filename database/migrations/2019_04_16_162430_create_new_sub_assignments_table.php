<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewSubAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_sub_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id');
            $table->string('d_table_work_direction');
            $table->integer('number_sub_assignment');
            $table->integer('work_row_index')->nullable();
            $table->string('d_table_workzone')->nullable();
            $table->date('d_table_time_start')->nullable();
            $table->date('d_table_time_finish')->nullable();
            $table->string('d_table_responsible_officer')->nullable();
            $table->text('d_table_list_completed_works')->nullable();
            $table->integer('d_table_quantity')->default(1);
            $table->float('d_table_price')->default(0.00);
            $table->string('d_table_currency')->default('MDL');
            $table->float('work_sum_row')->default(0.00);
            $table->integer('spares_row_index')->nullable();
            $table->string('d_table_spares_detail')->nullable();
            $table->string('d_table_spares_vendor_code')->nullable();
            $table->string('d_table_spares_unit_measurements')->nullable();
            $table->integer('d_table_spares_quantity')->default(1);
            $table->float('d_table_spares_price')->default(0.00);
            $table->string('d_table_spares_currency')->default('MDL');
            $table->float('spares_sum_row')->default(0.00);
            $table->string('work_is_locked')->nullable();
            $table->string('spares_is_locked')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_sub_assignments');
    }
}
