<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagesPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wages_payouts', function (Blueprint $table) {
            $table->id();
            $table->decimal('net_pay', 8,2);
            $table->decimal('tax', 8,2);
            $table->decimal('insurance_sum', 8,2);
            $table->decimal('soc_insurance', 8,2);
            $table->decimal('insurance_h_s', 8,2);
            $table->decimal('pension', 8,2);
            $table->text('equations'); //json
            $table->text('additional_earnings'); //json
            $table->text('additional_deductions'); //json
            $table->foreignId('user_id');
            $table->string('worked_days',10);
            $table->string('worked_hours',10);
            $table->date('incurred_at');
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
        Schema::dropIfExists('wages_payouts');
    }
}
