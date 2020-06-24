<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountOperationsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_operations_archive', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('author');
            $table->string('tag')->nullable();
            $table->string('category')->nullable();
            $table->text('comment')->nullable();
            $table->float('balance', 8, 2)->default(0.00);
            $table->string('status');
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
        Schema::dropIfExists('account_operations_archive');
    }
}
