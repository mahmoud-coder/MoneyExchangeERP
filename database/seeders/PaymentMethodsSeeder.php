<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create([
            'method' => 'Cash',
            'desc' => 'payment with cash',
        ]);
        PaymentMethod::create([
            'method' => 'Check',
            'desc' => 'payment with check',
        ]);
        PaymentMethod::create([
            'method' => 'Cards',
            'desc' => 'payment with debit / credit cards',
        ]);
        PaymentMethod::create([
            'method' => 'Transfer',
            'desc' => 'payment with pank transfers',
        ]);
    }
}
