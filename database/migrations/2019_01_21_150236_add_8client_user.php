<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add8clientUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable(); //додали ще одну колонку
            $table->string('fio')->nullable();
            $table->string('organization')->nullable();
            $table->string('phone')->nullable();
            $table->string('balance')->nullable();
            $table->string('discount')->nullable();
        });
        $demo_values = [
            [
                'id' => 8,
                'general_name' => '8 клиент',
                'user_id'=>8,
                'fio'=>'FIO',
                'organization' => 'Organization',
                'phone'=>'0909900099',
                'balance'=>'500',
                'discount'=>'10',
          ]
        ];

        DB::table('clients')->insert($demo_values);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('user_id');
                $table->dropColumn('fio');
                $table->dropColumn('organization');
                $table->dropColumn('phone');
                $table->dropColumn('balance');
                $table->dropColumn('discount');

        });
    }
}
