<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Transaction, EntityCustomer, IndividualCustomer, Employee, WagesPayout, User};
use DB;

class DashboardController extends Controller
{
    public function __invoke(){
        $this->authorize('view-transactions');
        $this->authorize('view-customers');
        $this->authorize('view-expenses');
        
        $today = date('Y-m-d');

        $top_individual_customer_countries = DB::select('SELECT ic.country_id, countries.name, count(ic.country_id) count from individual_customers ic join countries on ic.country_id = countries.id group by ic.country_id ORDER by count desc limit 5');
        $top_entity_customer_countries = DB::select('SELECT ec.country_id, countries.name, count(ec.country_id) count from entity_customers ec join countries on ec.country_id = countries.id group by ec.country_id ORDER by count desc limit 5');

        return view('admin.dashboard.index', [
            'main_menu' => $this->getAdminMenu(),
            'buy_orders_count' => Transaction::where('type', 1)->count(),
            'sell_orders_count' => Transaction::where('type', 2)->count(),
            'today' => $today,
            'today_buy_orders_count' => Transaction::where('type', 1)->where('date', $today)->count(),
            'today_sell_orders_count' => Transaction::where('type', 2)->where('date', $today)->count(),
            'entity_customers_count' => EntityCustomer::all()->count(),
            'individual_customers_count' => IndividualCustomer::all()->count(),
            'users_count' => User::all()->count(),
            'Lefted_employees_count' => Employee::whereNotNull('left_at')->count(),
            'working_employees_count' => Employee::whereNull('left_at')->count(),
            'top_individual_customer_countries' => $top_individual_customer_countries,
            'top_entity_customer_countries' => $top_entity_customer_countries,
            'last_wages_payout' => WagesPayout::where('incurred_at', WagesPayout::max('incurred_at'))->first()
        ]);
    }
}
