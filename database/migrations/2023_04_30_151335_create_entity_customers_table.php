<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('TIN')->nullable();
            $table->string('director_name')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->string('address')->nullable();
            $table->foreignId('activity_id')->nullable()->constrained();
            $table->unsignedBigInteger('share_capital')->nullable();
            $table->unsignedTinyInteger('restriction_status')->default(0);
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
        Schema::dropIfExists('entity_customers');
    }
}
