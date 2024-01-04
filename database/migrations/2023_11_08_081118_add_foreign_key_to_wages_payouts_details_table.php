<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToWagesPayoutsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wages_payouts_details', function (Blueprint $table) {
            $table->foreignId('wages_paid_id')->nullable()->after('employee_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wages_payouts_details', function (Blueprint $table) {
            $table->dropForeign(['wages_paid_id']);
        });
    }
}
