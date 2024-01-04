<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Currency, Transaction, Option, COA};
use Session;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('create-currencies');
        return view('admin.currencies.index', [
            'main_menu' => $this->getAdminMenu()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-currencies');
        return view('admin.currencies.currency', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'new'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create-currencies');
        $request->validate([
            'img' => 'required',
            'name' => 'required|unique:currencies',
            'symbol' => 'required|unique:currencies',
        ]);
        $currency = new Currency;
        $currency->fill($request->except('img'));
        if($request->hasFile('img')){
           $request->img->storeAs('public/coins',"$request->symbol.svg");
        }
        $currency->save();
        $currency->account()->create([
            'name' => json_encode( [ 'en' => $currency->symbol, 'lt' => $currency->symbol ] ),
            'code' => 741 . $currency->id,
            'type' => COA::CURRENT_ASSET | COA::GOODS | COA::SYSTEM_ACCOUNT
        ]);
        Session::flash('success', 'Your new Currency has been saved');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('create-currencies');
        return view('admin.currencies.currency', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'edit',
            'currency' => Currency::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('create-currencies');
        $request->validate([
            'name' => Rule::unique('currencies')->ignore($id),
            'symbol' => Rule::unique('currencies')->ignore($id),
        ]);
        $currency = Currency::find($id);
        $currency->fill($request->except('img'));
        if($request->hasFile('img')){
           $request->img->storeAs('public/coins',"$request->symbol.svg");
        }
        $currency->save();
        $currency->account()->update([
            'name' => json_encode( [ 'en' => $currency->symbol, 'lt' => $currency->symbol ] )
        ]);
        Session::flash('success', 'The new Currency has been saved');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('create-currencies');
        if($id == Option::get('default_currency_id')){
            return response()->json(['message'=>'This is the default currency, you can\'t delete it'], 403);
        }
        if(Transaction::where('from_currency', $id)->orWhere('to_currency', $id)->count() !== 0){
            return response()->json(['message'=>'There are transaction uses this currency, you can\'t delete it'], 403);
        }
        $currency = Currency::find($id);
        @unlink(storage_path("app/public/coins/$currency->symbol.svg"));
        $currency->delete();
        return ['success' => true];
    }
}
