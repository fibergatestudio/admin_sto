<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class AddGeneralNameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('general_name')->nullable();
        });

        /* Обновляем демонстрационные данные */
        DB::table('users')
            ->where('id', 1)
            ->update(['general_name' => 'Главный администратор']);
        
        DB::table('users')
            ->where('id', 2)
            ->update(['general_name' => 'Тестовый работник']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('general_name');
        });
    }
}
