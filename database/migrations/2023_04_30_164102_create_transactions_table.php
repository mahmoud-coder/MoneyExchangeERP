<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->foreignId('from_currency')->constrained('currencies');
            $table->decimal('from_amount', 20, 10);
            $table->foreignId('to_currency')->constrained('currencies');
            $table->decimal('to_amount', 20, 10);
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->decimal('fees', 12, 4)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained();
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
        Schema::dropIfExists('transactions');
    }
}
