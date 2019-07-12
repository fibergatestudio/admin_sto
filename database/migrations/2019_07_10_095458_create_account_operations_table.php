<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('author');
            $table->string('tag')->nullable();
            $table->string('category')->nullable();
            $table->text('comment')->nullable();
            $table->float('income', 8, 2)->default(0.00);
            $table->float('expense', 8, 2)->default(0.00);
            $table->float('balance', 8, 2)->default(0.00);
            $table->time('date');
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
        Schema::dropIfExists('account_operations');
    }
}
