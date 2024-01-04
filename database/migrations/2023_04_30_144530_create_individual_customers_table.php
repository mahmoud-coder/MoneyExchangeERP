<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable()->unique();
            $table->date('birthday')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('residence')->nullable()->constrained('countries');
            $table->string('id_card')->nullable();
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('validation_status')->default(1);
            $table->unsignedTinyInteger('restriction_status')->default(0);
            $table->unsignedTinyInteger('PEP')->default(0);
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
        Schema::dropIfExists('individual_customers');
    }
}
