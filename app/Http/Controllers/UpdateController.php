<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\COA;
use Artisan;
use File;
use Hash;
use DB;

class UpdateController extends Controller
{
    /**
     * NOTES
     * make sure there are no existed accounts in COA , that has the new accounts
     */
    
    public function __invoke(Request $request){
        abort(404);
    }

    private function update_coa()
    {
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
            'type' => COA::CURRENT_LIABILITY | COA::PAYBLE_EMPLOYEES_INCOME_TAX | COA::SYSTEM_ACCOUNT
        ]);
        COA::create([
            'name' => json_encode([
                'en' => 'Employee wages and related costs',
                'lt' => 'Darbuotojų darbo užmokestis ir su juo susijusios sąnaudos'
            ]),
            'code' => 6304,
            'type' => COA::EXPENSE | COA::WAGES | COA::SYSTEM_ACCOUNT
        ]);
    }

    private function equations()
    {
        Option::set('salary-tax-free-amount', json_encode([
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
