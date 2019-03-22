<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropWorkzoneForeignIndexOnAssignments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['workzone_id']);
            $table->dropColumn('workzone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Проблема, нужна проверка */
        // Schema::table('assignments', function (Blueprint $table) {
        //     // $table->unsignedInteger('workzone_id');
        //     // $table->foreign('workzone_id')->references('id')->on('workzones');
        //     $table->dropColumn('workzone_id');
        // });

        if (Schema::hasColumn('assignments', 'workzone_id'))
        {
            Schema::table('assignments', function (Blueprint $table)
            {
                $table->dropColumn('workzone_id');
            });
        }
    }
}
