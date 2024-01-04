<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Session;

class PaymentMethodsController extends Controller
{
    public function index(){
        $this->authorize('create-payment-methods');
        return view('admin.payment-methods.index', [
            'main_menu' => $this->getAdminMenu(),
        ]);
    }

    public function create(){
        $this->authorize('create-payment-methods');
        return view('admin.payment-methods.create-edit', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'new'
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'method' => 'required'
        ]);
        $pm = new PaymentMethod;
        $pm->fill($request->all());
        $pm->save();
        Session::flash('success', 'The new payment method has been saved');
        return back();
    }

    public function edit($id){
        $this->authorize('create-payment-methods');
        return view('admin.payment-methods.create-edit', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'edit',
            'pm' => PaymentMethod::find($id)
        ]);
    }

    public function update(Request $request, $id){
        $this->authorize('create-payment-methods');
        $request->validate([
            'method' => Rule::unique('payment_methods')->ignore($id)
        ]);
        $pm = PaymentMethod::find($id);
        $pm->fill($request->all());
        $pm->save();
        Session::flash('success', 'The payment method has been saved');
        return back();
    }

    public function destroy($id){
        $this->authorize('create-payment-methods');
        if(Transaction::where('payment_method_id', $id)->count() !== 0){
            return response()->json(['message' => 'There are transaction uses this payment method, you can\'t delete it'], 403);
        }
        PaymentMethod::find($id)->delete();
        return ['success' => true];
    }
}
