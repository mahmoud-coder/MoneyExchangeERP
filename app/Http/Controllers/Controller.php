<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Http\Resources\Customer as CustomerResource;
use App\Models\Customer;
use App\Models\Option;
use Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getAdminMenu(){
        $menu = [];
        if(Gate::check(['view-transactions', 'view-customers', 'view-expenses'])){
            $menu[] = [
                'url' => '/admin/',
                'name'=> 'Dashboard',
                'icon'=> 'menu-icon tf-icons ti ti-smart-home',
                'route'=> 'admin.index'
            ];
        }
        if(Gate::allows('create-transactions')){
            $menu[] = [
                'name' => 'Orders',
                'icon' => 'menu-icon tf-icons ti ti-coin',
                'route' => 'admin.orders',
                'submenu' => [
                    [
                        'url' => '/admin/orders/buy',
                        'name' => 'Buy',
                        'route' => 'admin.orders.buy'
                    ],
                    [
                        'url' => '/admin/orders/sell',
                        'name' => 'Sell',
                        'route' => 'admin.orders.sell'
                    ],
                    [
                        'url' => '/admin/orders/upload',
                        'name' => 'Upload',
                        'route' => 'admin.orders.upload'
                    ]
                ]
            ];
        }
        if(Gate::allows('view-transactions')){
            $menu[] = [
                'name'=>'Transactions',
                'icon' =>'menu-icon tf-icons ti ti-repeat',
                'route'=>'admin.transaction',
                'submenu'=>[
                    [
                        'url'=> '/admin/transaction/buy',
                        'name'=>'Buy',
                        'route'=>'admin.transaction.buy'
                    ],
                    [
                        'url'=> '/admin/transaction/sell',
                        'name'=>'Sell',
                        'route'=>'admin.transaction.sell'
                    ],
                    [
                        'url'=> '/admin/transaction',
                        'name'=>'Monitor',
                        'route'=>'admin.transaction.index'
                    ]
                ]
            ];
        }
        if(Gate::any(['create-transactions', 'view-customers'])){
            $customers_menu = [
                'name'=>'Customers',
                'icon' => 'menu-icon tf-icons ti ti-users',
                'route' => 'admin.customers',
                'submenu'=> []
            ];
            if(Gate::allows('create-transactions')){
                $customers_menu['submenu'][] = [
                    'name'=>'Individual ',
                    'url'=>'/admin/customers/individual/create',
                    'route'=>'admin.customers.individual.create'
                ];
                $customers_menu['submenu'][] = [
                    'name' => 'Entity',
                    'url' => '/admin/customers/entity/create',
                    'route'=>'admin.customers.entity.create'
                ];
            }
            if(Gate::allows('view-customers')){
                $customers_menu['submenu'][] = [
                    'name' => 'All',
                    'url' => '/admin/customers/all',
                    'route' => 'admin.customers.all'
                ];
            }
            $menu[] = $customers_menu;
        }
        if(Gate::allows('create-currencies')){
            $menu[] = [
                'name'=> 'Currencies',
                'icon'=>'menu-icon tf-icons ti ti-cash',
                'route'=>'admin.currency',
                'submenu'=>[
                    [
                        'name' => 'Add currency',
                        'url'=>'/admin/currency/create',
                        'route'=>'admin.currency.create'
                    ],
                    [
                        'name' => 'List of Currencies',
                        'url'=>'/admin/currency',
                        'route'=>'admin.currency.index'
                    ]
                ]
            ];
        }
        if(Gate::allows('create-payment-methods')){
            $menu[] = [
                'name' => 'Payment methods',
                'route' => 'admin.payment-methods',
                'icon'=>'menu-icon tf-icons ti ti-credit-card',
                'submenu'=>[
                    [
                        'name'=>'Add payment method',
                        'url' => '/admin/payment-methods/create',
                        'route'=>'admin.payment-methods.create'
                    ],
                    [
                        'name'=>'List of Payment methods',
                        'url' => '/admin/payment-methods',
                        'route'=>'admin.payment-methods.index'
                    ]
                ]
            ];
        }
        if(Gate::any(['view-expenses', 'create-expenses'])){
            $accounting_menu = [
                'name'=>'Accounting',
                'route'=>'admin.accounting',
                'icon'=>'menu-icon tf-icons ti ti-calculator',
                'submenu'=>[]
            ];
            $accounting_menu['submenu'][] = [
                'name' => 'Accounts Chart',
                'url' => '/admin/accounting/coa',
                'route' => 'adming.accounting.coa'
            ];
            $accounting_menu['submenu'][] = [
                'name' => 'General Journal',
                'url' => '/admin/accounting/general-journal',
                'route' => 'adming.accounting.general_journal'
            ];
            $accounting_menu['submenu'][] = [
                'name' => 'Ledger',
                'url' => '/admin/accounting/ledger',
                'route' => 'adming.accounting.ledger'
            ];
            /*
            if(Gate::allows('create-expenses')){
                $accounting_menu['submenu'][] = [
                    'name'=>'Add Expense',
                    'url'=>'/admin/accounting/expenses/create',
                    'route'=>'admin.accounting.expenses.create'
                ];
            }
            */
            if(Gate::allows('view-expenses')){
                /*
                $accounting_menu['submenu'][] = [
                    'name' => 'All Expenses',
                    'url'=>'admin/accounting/expenses',
                    'route'=>'admin.accounting.expenses.index'
                ];
                
                $accounting_menu['submenu'][] = [
                    'name' => 'Statement',
                    'url' => 'admin/accounting/statement',
                    'route' => 'admin.accounting.statement'
                ];
                */
                $accounting_menu['submenu'][] = [
                    'name' => 'Trial Balance',
                    'url' => 'admin/accounting/trial_balance',
                    'route' => 'admin.accounting.trial_balance'
                ];
                $accounting_menu['submenu'][] = [
                    'name' => 'P & L Statement',
                    'url' => 'admin/accounting/statement/profit_loss',
                    'route' => 'admin.accounting.statement.profit_loss'
                ];
                $accounting_menu['submenu'][] = [
                    'name' => 'Cost Grid - FIFO',
                    'url' => 'admin/accounting/reports',
                    'route' => 'admin.accounting.reports'
                ];
                $accounting_menu['submenu'][] = [
                    'name' => 'Salaries Payouts',
                    'url' => 'admin/wages-payout',
                    'route' => 'admin.wages-payout'
                ];
                $accounting_menu['submenu'][] = [
                    'name' => 'Turnover',
                    'url' => '/admin/transaction/turnover',
                    'route' => 'admin.transaction.turnover'
                ];
            }
            $menu[] = $accounting_menu;
        }
        if(Gate::allows('create-users')){
            $menu[] = [
                'name'=>'Users',
                'route'=>'admin.users',
                'icon'=>'menu-icon tf-icons ti ti-user-circle',
                'submenu'=>[
                    [
                        'name'=> 'Add New',
                        'route'=>'admin.users.create',
                        'url'=>'/admin/users/create'
                    ],
                    [
                        'name' => 'List of Users',
                        'route'=> 'admin.users.index',
                        'url'=>'/admin/users'
                    ]
                ]
            ];
        }
        if(Gate::any(['create-employees', 'view-employees'])){
            $employees = [
                'name'=>'Employees',
                'route'=>'admin.employees',
                'icon' => 'menu-icon tf-icons ti ti-users',
                'submenu' => []
            ];
            if(Gate::allows('create-employees')){
                $employees['submenu'][] = [
                    'name'=> 'Add New',
                    'route'=>'admin.employees.create',
                    'url'=>'/admin/employees/create'
                ];
            }
            if(Gate::allows('view-employees')){
                $employees['submenu'][] = [
                    'name' => 'List of Employees',
                    'route'=> 'admin.employees.index',
                    'url'=>'/admin/employees'
                ];
            }
            $menu[] = $employees;
        }
        // if(Gate::allows('export-import-data')){
        //     $menu[] = [
        //         'name'=> 'Data',
        //         'route' => 'admin.data',
        //         'icon'=> 'menu-icon tf-icons ti ti-database',
        //         'submenu'=>[
        //             [
        //                 'name' => 'Export / Import',
        //                 'route' => 'admin.data.import_export',
        //                 'url'=>'/admin/data/import-export'
        //             ]
        //         ]
        //     ];
        // }
        if(Gate::allows('change-settings')){
            $menu[] = [
                'url' => '/admin/settings',
                'name'=> 'Settings',
                'icon'=> 'menu-icon tf-icons ti ti-settings',
                'route'=> 'admin.settings'
            ];
        }
        return $menu;
    }

    protected function getCountries(){
        return Country::get(['id', 'symbol', 'name']);
    }
    
    /**
     * get all currencies we can buy and sell, that exclude the default currency and excluded coins
     */
    protected function getCurrencies(){
        $excluded = [Option::get('default_currency_id')];
        return Currency::whereNotIn('id',$excluded)->get()->makeHidden(['created_at','updated_at' ]);
    }

    protected function getPaymentMethods(){
        return PaymentMethod::all()->makeHidden(['created_at','updated_at' ]);
    }

    protected function getCustomers(){
        return CustomerResource::collection(Customer::all())->toJson();
    }

}
