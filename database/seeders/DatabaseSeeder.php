<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Option;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(! User::find(1)){
            $user = new User;
            $user->name = 'admin';
            $user->email = 'ceo@monybeat.eu';
            $user->password = bcrypt('123456');
            $user->role = 0;
            $user->save();
        }

        $this->call([
            CurrencySeeder::class,
            CountrySeeder::class,
            PaymentMethodsSeeder::class,
            ActivitySeeder::class,
            AccountSeeder::class,
            EmployeeSeeder::class
        ]);

        // Options
        /* 1:  'default_currency_id' created in CurrencySeeder::class */
        /* 2: */ Option::set('sumsub-customer-create', true);
        /* 3: */ Option::set('orders-use-stored-exchange-rate', false);
        /* 4: */ Option::set('orders-use-stored-fees', false);
        /* 5: */ Option::set('transactions-show-mini-summary', false);
        /* 6: */ Option::set('salary-tax-free-amount', json_encode([
            [
                'range' => [0, 625],
                'npd' => '-1',
                'explain' => 'The tax-free income for less than 840 is 625 , so the are no tax'
            ],
            [
                'range' => [625, 840],
                'npd' => 'earnings - 625',
                'explain' => 'The tax-free amount: (total earning: earnings) - 625 =  npd ; tax: tax_rate of (earnings - npd) = value'
            ],
            [
                'range' => [840, 1926],
                'npd' => '625 - (0.42 * (earnings - 840))',
                'explain' => 'the tax-free amount: 625 - [0.42 x [(total earning: earnings) - 840]] = npd; tax: tax_rate of (earnings - npd) = value'
            ],
            [
                'range' => [1926],
                'npd' => '400 - (0.18 * (earnings - 642))',
                'explain' => 'the tax-free amount: 400 - [0.18 x [(total earning: earnings) - 642]] = npd; tax: tax_rate of (earnings - npd) = value'
            ]
        ]));
    }
}
