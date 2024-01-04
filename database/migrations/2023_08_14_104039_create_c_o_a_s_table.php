<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * type field: 
         *   first 3 bits for the main accounts
         *   the bit 4 to bit 7: is sub account of a main account
         *   the bit 8 is if the account is a system account (deletable?), 1 = system account and not deletable.
         *   @see app\Models\COA.php
         */
        Schema::create('coa', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300);
            $table->unsignedMediumInteger('code');
            $table->foreignId('item_id')->nullable();
            $table->unsignedTinyInteger('type');
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
        Schema::dropIfExists('coa');
    }
}
