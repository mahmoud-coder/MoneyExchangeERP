<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\{TransactionCollection, CustomerCollection, UserCollection, ExpenseCollection, CurrencyCollection, PaymentMethodCollection, EmployeeCollection, WagesPayoutCollection};
use App\Models\{Transaction, Customer, IndividualCustomer, EntityCustomer, User, Expense, Currency, PaymentMethod, COA, GeneralJournal, GeneralJournalDetail, Employee, WagesPayout, Option};
use App\Accounting\{FIFO, Ledger};
use Exception;
use DB;
use Auth;

define('PAGE_SIZE' , 10);

class AJAXController extends Controller
{
    public function transactions(Request $request)
    {
        $this->authorize('view-transactions');
        $query = Transaction::query();
        if($request->has('search_id')){
            $id = str_replace('mbtr','', strtolower($request->search_id) );
            $query->where('id',$id);
        }
        if($request->has('search_date')){
            $query->where('date' , $request->search_date);
        }
        if($request->has('search_customer_id')){
            $query->where('customer_id', $request->search_customer_id);
        }
        if($request->has('search_coin_id')){
            $query->whereRaw('(`from_currency` = ? OR `to_currency` = ?)',[$request->search_coin_id,$request->search_coin_id]);
        }
        if($request->type == 'buy'){
            return new TransactionCollection($query->where('type',1)->orderBy('id','desc')->paginate(PAGE_SIZE));
        }elseif($request->type == 'sell'){
            return new TransactionCollection($query->where('type',2)->orderBy('id','desc')->paginate(PAGE_SIZE));
        }
        return new TransactionCollection($query->orderBy('id','desc')->paginate(PAGE_SIZE));
    }

    public function users()
    {
        $this->authorize('create-users');
        return new UserCollection(User::paginate(PAGE_SIZE));
    }

    public function employees()
    {
        $this->authorize('view-employees');
        return new EmployeeCollection(Employee::paginate(PAGE_SIZE));
    }
    
    public function wages_payout()
    {
        $this->authorize('create-expenses');
        return new WagesPayoutCollection(WagesPayout::paginate(PAGE_SIZE));
    }

    public function customers(Request $request)
    {
        $this->authorize('view-customers');
        $query = Customer::query();
        if($request->has('search_id')){
            $query->where('id', str_replace('mbct','', strtolower($request->search_id) ));
        }
        if($request->has('search_name')){
            $query->whereHasMorph('customerable',
                [IndividualCustomer::class, EntityCustomer::class ], 
                function(Builder $query, $type) use($request){
                    $query->where('name', 'like', "%$request->search_name%" );
                    if($type == IndividualCustomer::class){
                        $query->orWhere('surname', 'like', "%$request->search_name%");
                    }
                });
        }
        return new CustomerCollection($query->orderBy('id', 'desc')->paginate(PAGE_SIZE));
    }

    public function expenses()
    {
        $this->authorize('view-expenses');
        return new ExpenseCollection( Expense::paginate(PAGE_SIZE));
    }

    public function currencies()
    {
        $this->authorize('create-currencies');
        return new CurrencyCollection(Currency::paginate(PAGE_SIZE));
    }

    public function payment_methods()
    {
        $this->authorize('create-payment-methods');
        return new PaymentMethodCollection(PaymentMethod::paginate(PAGE_SIZE));
    }

    public function fifo(Request $request)
    {
        $this->authorize('view-expenses');
        try{
            $cost_basis = (new FIFO($request->currency, $request->maximum_invoices, $request->begining_inventory, $request->invoice))->get();
            if($request->has('asHTML')){
                return [
                    'markup' => view('partials.fifo-table',[
                        'data' => $cost_basis['data'],
                        'currency_id' => $request->currency
                    ])->render(),
                    'meta' => $cost_basis['meta']
                ];
            }else{
                return $cost_basis;
            }
        }catch(Exception $e){
            if($request->has('asHTML')){
                return ['markup' => view('partials.fifo-table')->with('error', $e)->render()];
            }else{
                return ['success' =>false, 'message'=>$e->getMessage()];
            }
        }
    }


    public function coins()
    {
        return $this-> getCurrencies();
    }

    public function new_account(Request $request)
    {
        $this->authorize('create-expenses');
        $account = COA::create([
            'name' => json_encode(['en' => $request->name_en, 'lt' => $request->name_lt]),
            'code' => $request->code,
            'type' => $request->type,
        ]);
        return ['message' =>'New Account Added', 'new_account' => $account];
    }

    public function delete_account(COA $account)
    {
        if( $account->type & 128 )
            return response()->json(['message'=> 'This Account is a system account, can\'t be deleted'], 400);
        if( DB::select('select *  from general_journal_details where account_id = ?', [$account->id]))
            return response()->json(['message'=> 'This Account is used in the General Journal'], 400);
        $account->delete();
        return ['success' => true, 'message' => 'The account is deleted'];
    }
    
    public function edit_account(Request $request, COA $account)
    {
        $account->name = json_encode(['en' => $request->name_en, 'lt' => $request->name_lt]);
        $account->code = $request->code;
        if(! ($account->type & 128)){ // for non-system account , you can change its type
            $account->type = $request->type;
        }
        $account->save();
        return ['success' => true, 'account' => $account, 'message' => 'The account has been edited'];
    }

    public function store_general_journal(Request $request)
    {
        $entry = GeneralJournal::create([
            'date' => $request->date,
            'notes' => $request->notes,
            'user_id' => Auth::id()
        ])->details()->createMany($request->details);
        return ['success' => true, 'message' => 'The General Journal Entry is saved', 'entry' => $entry];
    }

    public function Ledger($account_id)
    {
        $details = GeneralJournalDetail::with('general_journal.itemable')->where('account_id', $account_id)->get()->sortBy('general_journal.date')->flatten();
        $running_balance = 0;
        foreach($details as $row){
             // notes
            if($row->general_journal->notes){
                if(! is_object($row->general_journal->notes)){
                    $row->general_journal->notes = (object) [
                        'type' => 'text',
                        'text' => $row->general_journal->notes
                    ];
                }
            }elseif($row->general_journal->itemable_type == 'App\Models\Transaction'){
                $row->general_journal->notes = (object) [
                    'type' => 'html',
                    'markup' => ($row->general_journal->itemable->type == 1 ? 'Purchase' : 'Sale') . ', invoice: <a href="/admin/transaction/' . $row->general_journal->itemable_id . '" target="__blank">MBTR' . $row->general_journal->itemable_id . '</a>',
                    'text' => ($row->general_journal->itemable->type == 1 ? __('Purchase') : __('Sale') ) .', '. __('invoice') . ': MBTR' . $row->general_journal->itemable_id
                ];
            }else{
                $row->general_journal->notes = (object) [
                    'type' => 'text',
                    'text' => __('no notes found in the general journal entry')
                ];
            }
            
             // running balance
             if($row->type == 'Debit'){
                $running_balance += $row->amount;
             }else{
                $running_balance -= $row->amount;
             }
             if($running_balance >=0){
                $row->running_balance_debit = (float) $running_balance;
                $row->running_balance_credit = ' ';
             }else{
                $row->running_balance_debit = ' ';
                $row->running_balance_credit = (float) abs($running_balance);
             }
        }
        return $details;
    }

    function turnover(Request $request)
    {
        return DB::select('call transactions_summary(?,?)', [$request->from, $request->to]);
    }

    function set_option(Request $request)
    {
        try{
            Option::set($request->key, $request->value);
            return ['success' => true];
        }catch(Exception $e){
            return ['success' => false, 'message' =>$e->getMessage()];
        }
    }

    function revenues_and_expenses(Request $request)
    {
        $ledger = new Ledger;
        return $ledger->revenues_and_expenses_accounts_balances($request->from, $request->to);
    }
}
