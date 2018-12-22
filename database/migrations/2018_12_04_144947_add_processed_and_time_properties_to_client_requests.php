<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcessedAndTimePropertiesToClientRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->boolean('processed')->default(0);
            $table->time('appointment_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->dropColumn('processed');
            $table->dropColumn('appointment_time');
        });
    }
}
