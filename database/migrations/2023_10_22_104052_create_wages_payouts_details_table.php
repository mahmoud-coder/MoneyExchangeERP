<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagesPayoutsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wages_payouts_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wages_payout_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->restrictOnDelete();
            $table->decimal('salary', 6, 2);
            $table->decimal('pension', 6, 2);
            $table->decimal('npd', 6, 2);
            $table->decimal('tax', 6, 2);
            $table->decimal('insurance', 6, 2);
            $table->decimal('h_s_insurance', 6, 2);
            $table->text('additional_earnings_values'); //json
            $table->text('additional_deductions_values'); //json
            $table->decimal('total_deducted', 6, 2);
            $table->decimal('net_pay', 6, 2);
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
        Schema::dropIfExists('wages_payouts_details');
    }
}
