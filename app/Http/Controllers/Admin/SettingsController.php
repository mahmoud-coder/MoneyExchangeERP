<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use Session;

class SettingsController extends Controller
{
    public function __invoke(){
        $this->authorize('change-settings');
        return view('admin.settings', [
            'main_menu' => $this->getAdminMenu(),
            'options' => Option::pluck('value', 'name')
        ]);
    }

    public function store(Request $request){
        $this->authorize('change-settings');
        Option::set('sumsub-customer-create', $request->input('sumsub-customer-create'));
        Option::set('orders-use-stored-exchange-rate', $request->input('orders-use-stored-exchange-rate'));
        Option::set('orders-use-stored-fees', $request->input('orders-use-stored-fees'));
        Option::set('transactions-show-mini-summary', $request->input('transactions-show-mini-summary'));
        Session::flash('success', 'the settings has been updated successfully');
        return back();
    }
}
