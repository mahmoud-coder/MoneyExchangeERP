<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\COA;
use App\Models\Currency;
use App\Models\Option;


class AccountSeeder extends Seeder
{
    public function run(){
        COA::create([
            'name' => json_encode([
                'en' => 'Cash',
                'lt' => 'grynųjų pinigų'
            ]),
            'code' => 271,
            'type' => COA::CURRENT_ASSET | COA::CASH | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Cost of Sold Goods',
                'lt' => 'Parduotų prekių kaina'
            ]),
            'code' => 327,
            'type' => COA::EXPENSE | COA::COST_OF_SOLD_GOODS | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Fees',
                'lt' => 'Mokesčiai'
            ]),
            'code' => 321,
            'type' => COA::EXPENSE | COA::FEES | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Selling Revenue',
                'lt' => 'Pardavimo pajamos'
            ]),
            'code' => 876,
            'type' => COA::REVENUE | COA::SELLING_REVENUE | COA::SYSTEM_ACCOUNT
        ]);

        // accounts used in record salary 
        COA::create([
            'name' => json_encode([
                'en' => 'Payable Salary',
                'lt' => 'Mokėtinas darbo užmokestis'
            ]),
            'code' => 4480,
            'type' => COA::CURRENT_LIABILITY | COA::PAYBLE_SALARY | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Payable Social Insurance',
                'lt' => 'Mokėtinos socialinio draudimo įmokos'
            ]),
            'code' => 4482,
            'type' => COA::CURRENT_LIABILITY | COA::PAYBLE_INSURANCE | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Payable Income Tax',
                'lt'=> 'Mokėtinas gyventojų pajamų mokestis'
            ]),
            'code' => 4481,
            'type' => COA::CURRENT_LIABILITY | COA::PAYBLE_EMPLOYEES_ICOME_TAX | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Employee wages and related costs',
                'lt' => 'Darbuotojų darbo užmokestis ir su juo susijusios sąnaudos'
            ]),
            'code' => 6304,
            'type' => COA::EXPENSE | COA::WAGES | COA::SYSTEM_ACCOUNT
        ]);

        $currencies = Currency::where('id', '!=', Option::get('default_currency_id'))->get();
        foreach($currencies as $currency){
            COA::create([
                'name' => json_encode( [ 'en' => $currency->symbol, 'lt' => $currency->symbol ] ),
                'item_id' => $currency->id,
                'code' => 741 . $currency->id,
                'type' => COA::CURRENT_ASSET | COA::GOODS | COA::SYSTEM_ACCOUNT
            ]);
        }
    }
}