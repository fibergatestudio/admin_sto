<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('user_role')->nullable();          
            $table->string('tg_assignment_notification')->default('1')->nullable(); 
            $table->string('tg_income_notification')->default('1')->nullable();
            $table->string('tg_expense_notification')->default('1')->nullable();
            $table->string('tg_fine_notification')->default('1')->nullable();
            $table->string('tg_bonus_notification')->default('1')->nullable();
            $table->string('tg_supply_order_notification')->default('1')->nullable();
            $table->string('tg_client_master_notification')->default('1')->nullable();
            $table->timestamps();
        });

        $admin_values = [
            [
                'id' => 1,
                'user_id' => 1,
                'user_role' => 'admin'
            ]
        ];
        $employee_values = [
            [
                'id' => 2,
                'user_id' => 2,
                'user_role' => 'employee'
            ]
        ];

        DB::table('user_options')->insert($admin_values);
        DB::table('user_options')->insert($employee_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_options');
    }
}
