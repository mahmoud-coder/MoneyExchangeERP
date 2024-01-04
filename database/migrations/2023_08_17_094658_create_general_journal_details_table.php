<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralJournalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_journal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_journal_id');
            $table->foreignId('account_id');
            $table->decimal('amount', 20, 10);
            $table->tinyInteger('type'); // 0 = debit, 1 = credit
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
        Schema::dropIfExists('general_journal_details');
    }
}
