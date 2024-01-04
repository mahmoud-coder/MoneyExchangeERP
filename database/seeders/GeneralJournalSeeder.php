<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\COA;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;


class GeneralJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GeneralJournal::create([
            'date' => '2023-8-1',
            'notes' => 'testing'
        ])->details()->createMany([
            [
                'account_id' => COA::get_fees_account()->id,
                'amount' => 300,
                'type' => 'debit'
            ],
            [
                'account_id' => COA::get_cash_account()->id,
                'amount' => 300,
                'type' => 'credit'
            ]
        ]);
    }
}
