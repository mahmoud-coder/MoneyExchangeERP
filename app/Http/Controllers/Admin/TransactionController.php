<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Currency, Option, Transaction};
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\Transaction as TransactionResource;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Excel\Imports\TransactionsImport;
use App\Accounting\GeneralJournal;
use Exception;
use DB;
use Auth;

class TransactionController extends Controller
{
    public function buy_order(){
        $this->authorize('create-transactions');
        return view("admin.orders.buy-sell", [
            'type' => 'buy',
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
            'payment_methods' => $this->getPaymentMethods(),
            'customers' => $this->getCustomers(),
            'use_stored_exchange_rate' => Option::get('orders-use-stored-exchange-rate'),
            'use_stored_fees' => Option::get('orders-use-stored-fees')
        ]);
    }

    public function sell_order(){
        $this->authorize('create-transactions');
        return view('admin.orders.buy-sell', [
            'type' => 'sell',
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
            'payment_methods' => $this->getPaymentMethods(),
            'customers' => $this->getCustomers(),
            'use_stored_exchange_rate' => Option::get('orders-use-stored-exchange-rate'),
            'use_stored_fees' => Option::get('orders-use-stored-fees')
        ]);
    }

    public function upload()
    {
        $this->authorize('create-transactions');
        return view('admin.orders.upload', [
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
        ]);
    }

    public function upload_sheet(Request $request)
    {
        $this->authorize('create-transactions');
        $file = $request->file('file');
        $file->storeAs('sheets', 'transactions.xlsx' );
    }

    public function upload_analysis()
    {
        $transactions = (new TransactionsImport(2, 1000000, null))->toArray('/sheets/transactions.xlsx')[0];
        $transactions = collect($transactions);

        return [
            'sellCount' => $transactions->pluck(2)->filter(fn($t)=>trim(strtolower($t)) == 'sell')->count(),
            'buyCount' => $transactions->pluck(2)->filter(fn($t)=>trim(strtolower($t)) == 'buy')->count()
        ];
    }

    public function create_from_uploaded_sheet(Request $request)
    {
        $this->authorize('create-transactions');
        $import = new TransactionsImport(2, 1000000, $request->currency_id);
        $import->import('/sheets/transactions.xlsx');
    }

    public function index(){
       $this->authorize('view-transactions');
       return view('admin.transactions.monitor',[
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
            'customers' => $this->getCustomers(),
            'show_mini_summary' => Option::get('transactions-show-mini-summary')
       ]);
    }
    
    public function transaction_buy(){
        $this->authorize('view-transactions');
        return view('admin.transactions.monitor',[
            'type' => 'buy',
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
            'customers' => $this->getCustomers(),
            'show_mini_summary' => Option::get('transactions-show-mini-summary')
        ]);
    }
    public function transaction_sell(){
        $this->authorize('view-transactions');
        return view('admin.transactions.monitor',[
            'type' => 'sell',
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
            'customers' => $this->getCustomers(),
            'show_mini_summary' => Option::get('transactions-show-mini-summary')
        ]);
    }

    public function store(Request $request){
        $this->authorize('create-transactions');
        $default_currency_id = Option::get('default_currency_id');
        if($request->type == 1){ // buy
            $request->merge(['from_currency' => $default_currency_id, 'user_id' => Auth::id()]);
        }else{ // sell
            $request->merge(['to_currency' => $default_currency_id, 'user_id' => Auth::id()]);
        }
        
        $request->validate([
            'date' => 'required',
            'type' => 'required',
            'from_currency' => 'required',
            'to_currency' => 'required',
            'from_amount' => 'required',
            'to_amount' => 'required',
            'customer_id' => 'required',
            'payment_method_id' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $transaction = Transaction::create($request->all());
            $gj = new GeneralJournal;
            $gj->save_transaction_entity($transaction);
            DB::commit();
            return ['success' => true, 'message' => "the new transaction has been saved"];
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Transaction $transaction){
        $this->authorize('create-transactions');
        $default_currency_id = Option::get('default_currency_id');
        if($request->type == 1){ // buy
            $request->merge(['from_currency' => $default_currency_id]);
        }else{ // sell
            $request->merge(['to_currency' => $default_currency_id]);
        }
        $request->validate([
            'date' => 'required',
            'type' => 'required',
            'from_currency' => 'required',
            'to_currency' => 'required',
            'from_amount' => 'required',
            'to_amount' => 'required',
            'customer_id' => 'required',
            'payment_method_id' => 'required'
        ]);
        try{
            DB::beginTransaction();
            $transaction->update($request->all());
            $transaction->journal_entry()->delete();
            $gj = new GeneralJournal;
            $gj->save_transaction_entity($transaction);
            DB::commit();
            return ['success' => true, 'message' => "the MBTR{$transaction->id} transaction has been edited"];
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function edit(Request $request, Transaction $transaction){
        $this->authorize('create-transactions');
        return view('admin.orders.buy-sell', [
            'type' => 'edit',
            'transaction' => $transaction,
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
            'payment_methods' => $this->getPaymentMethods(),
            'customers' => $this->getCustomers(),
            'use_stored_exchange_rate' => Option::get('orders-use-stored-exchange-rate'),
            'use_stored_fees' => Option::get('orders-use-stored-fees')
        ]);
    }
    public function destroy(Request $request, Transaction $transaction){
        $this->authorize('create-transactions');
        try{
            $transaction->delete();
            return ['success' => true];
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', [
            'main_menu' => $this->getAdminMenu(),
            'transaction' => (new TransactionResource($transaction))->resolve()
        ]);
    }
    
    public function print($id){
        return view('admin.transactions.print',[
            'transaction' => (new TransactionResource(Transaction::find($id)))->resolve()
        ]);
    }

    public function pdf($id){
        $pdf = PDF::loadview('admin.transactions.pdf', [
            'transaction' => (new TransactionResource(Transaction::find($id)))->resolve()
        ]);
        return $pdf->stream();
    }

    public function turnover()
    {
        return view('admin.transactions.turnover', [
            'main_menu' => $this->getAdminMenu(),
            'currencies' => $this->getCurrencies(),
        ]);
    }
}
