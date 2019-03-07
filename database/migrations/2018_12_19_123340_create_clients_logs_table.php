<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id'); // foreign
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedInteger('author_id'); // foreign
            $table->foreign('author_id')->references('id')->on('users');
            $table->string('text');
            $table->string('type');
            $table->timestamps();
        });
    
        $demo_values = [
            ['id' => 1, 'client_id' => 1, 'author_id' => 1, 'text' => 'Тестовая запись лога по тестовому клиенту', 'type' => 'тип']
        ];

        DB::table('clients_logs')->insert($demo_values);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_logs');
    }
}
