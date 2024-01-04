<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\Option;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // the default currency
        $default = Currency::create([
                    'name' => 'Euro',
                    'symbol' => 'EURO'
                ]);
        Option::set('default_currency_id', $default->id);
        

        Currency::create([
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'purchase_exchange_rate' => 26037.50,
            'purchase_fees' => 120,
            'selling_exchange_rate' => 24150.72,
            'selling_fees' => 140
        ]);
        
        Currency::create([
            'name' => 'Ethereum',
            'symbol' => 'ETH',
            'purchase_exchange_rate' => 1694.10,
            'purchase_fees' => 130,
            'selling_exchange_rate' => 1579.52,
            'selling_fees' => 115
        ]);
        
        Currency::create([
            'name' => 'Tether',
            'symbol' => 'USDT',
            'purchase_exchange_rate' => 0.96,
            'purchase_fees' => 130,
            'selling_exchange_rate' => 0.89,
            'selling_fees' => 115
        ]);
        
        Currency::create([
            'name' => 'USD Coin',
            'symbol' => 'USDC',
            'purchase_exchange_rate' => 0.95,
            'purchase_fees' => 130,
            'selling_exchange_rate' => 0.89,
            'selling_fees' => 115
        ]);
      
        Currency::create([
            'name' => 'BNB',
            'symbol' => 'BNB',
            'purchase_exchange_rate' =>312,
            'purchase_fees' => 140,
            'selling_exchange_rate' => 290,
            'selling_fees' => 105
        ]);
    }
}
