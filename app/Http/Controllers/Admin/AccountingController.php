<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Expense, COA, GeneralJournal, Option, Employee, WagesPayout};
use App\Excel\Exports\GeneralJournalsExport;
use Carbon\{Carbon, CarbonPeriod};
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Accounting\Ledger;
use App;
use Auth;
use DB;
use Exception;

class AccountingController extends Controller
{
    public function statement(Request $request){
        $this->authorize('view-expenses');
        
        // once expenses
        $once_expenses_query = Expense::where('periodic_status', 0);
        if($request->from) $once_expenses_query->where('date','>=',$request->from);
        if($request->to) $once_expenses_query->where('date', '<=', $request->to);
        $once_expenses = $once_expenses_query->get();
        $once_expenses_sum = $once_expenses->sum('amount');

        // Monthly expenses
        /**
         * every monthly expense in the DB has 'from' and 'to' dates {the `to` can be null}
         * we compare the expense `from` and `to` , with the request `to` and `from` to get a range 
         * this range defined by $range_start, $range_end variables
         * every month in this range will be a monthly expense 
         * 
         * if the request `to` is null , then we consider it as today
         */
        $monthly_expenses= Expense::where('periodic_status', 1)->get();
        $today = date('Y-m-d');
        $resolved_monthly_expenses = [];
        $monthly_expenses_sum = 0;
        foreach($monthly_expenses as $expense){
            if(is_null($expense->to)) $expense->to = $today;
            if($request->from){
                $range_start = $expense->from > $request->from ? $expense->from : $request->from;
            }else{
                $range_start = $expense->from;
            }
            if($request->to){
                $range_end = $expense->to < $request->to ? $expense->to : $request->to;
            }else{
                $range_end = $expense->to < $today ? $expense->to : $today;
            }

            if($range_start >= $range_end) continue; // this expanse is entirely outside the request range {`from` and `to`}

            // get every month in the range
            $period = CarbonPeriod::create($range_start, '1 month', $range_end);

            foreach($period as $monthly_expense){
                $resolved_expense = clone $expense;
                $resolved_expense->date = $monthly_expense->format('Y-m'); 
                $resolved_monthly_expenses[] = $resolved_expense;
                $monthly_expenses_sum += $resolved_expense->amount;
            }
        }

        // sort expenses by its date
        $once_expenses = $once_expenses->sortBy('date');
        usort($resolved_monthly_expenses, fn($a, $b) => $a->date <=> $b->date);

        return view('admin.accounting.statement', [
            'main_menu' => $this->getAdminMenu(),
            'from' => $request->from,
            'to' => $request->to,
            'once_expenses' => $once_expenses,
            'once_expenses_sum' => $once_expenses_sum,
            'monthly_expenses' =>$resolved_monthly_expenses,
            'monthly_expenses_sum' => $monthly_expenses_sum,
        ]);
    }

    public function reports(){
        $this->authorize('view-expenses');
        return view('admin.accounting.reports', [
            'main_menu' => $this->getAdminMenu()
        ]);
        
    }

    public function coa(){
        $this->authorize('create-expenses');
        return view('admin.accounting.coa', [
            'main_menu' => $this->getAdminMenu(),
            'accounts' => COA::all()
        ]);
    }

    public function general_journal()
    {
        $this->authorize('create-expenses');
        return view('admin.accounting.general_journal', [
            'main_menu' => $this->getAdminMenu(),
            'accounts' => COA::all()
        ]);
    }

    public function general_journal_entries(Request $request)
    {
        $query = GeneralJournal::with('details.account');
        if($request->from)  $query->where('date','>=',$request->from);
        if($request->to)  $query->where('date','<=',$request->to);
        $entries = $query->orderBy('date')->orderBy('id')->get();

        App::setLocale($request->language ?? 'en');
        if($request->has('as_excel')){
           return (new GeneralJournalsExport($entries, $request->language))->download('general_journal.xlsx');
        }elseif($request->has('as_pdf')){
            $pdf = PDF::loadview('admin.accounting.entries', [
                'entries' => $entries,
                'lang' => $request->language,
                'company_name' => Option::get('company-name', 'UAB MoneyBeat'),
                'company_code' => Option::get('company-code', '306008398'),
            ]);
            return $pdf->stream('UTF-8');
        }else{
            return $entries;
        }
    }
    
    public function destroy_general_journal_entries(GeneralJournal $entry)
    {
        $entry->delete();
        return ['success' => true, 'message' => 'This General Journal and its data has been deleted'];
    }

    public function statement_profit_loss()
    {
        $this->authorize('view-expenses');
        $ledger = new Ledger;
        $revenues_expenses = $ledger->revenues_and_expenses_accounts_balances();
        
        return view('admin.accounting.profit-loss-statement', [
            'main_menu' => $this->getAdminMenu(),
            'revenues' => isset($revenues_expenses['Revenue']) ? json_encode($revenues_expenses['Revenue']) : null,
            'expenses' => isset($revenues_expenses['Expense']) ? json_encode($revenues_expenses['Expense']) : null
        ]);
    }

    public function ledger(Request $request)
    {
        $this->authorize('view-expenses');
        if($request->has('as_pdf')){
            App::setLocale($request->language);
            $pdf = PDF::loadview('admin.accounting.ledger-pdf',[
                'lang' => $request->language,
                'account' => COA::find($request->account_id),
                'data' => (new AJAXController)->ledger($request->account_id)
            ]);
            return $pdf->stream('UTF-8');
        }
        return view('admin.accounting.ledger', [
            'main_menu' => $this->getAdminMenu(),
            'accounts' => COA::all()
        ]);
    }

    public function trial_balance(Request $request)
    {
        $this->authorize('view-expenses');

        return view('admin.accounting.trial_balance', [
            'main_menu' => $this->getAdminMenu(),
            'accounts' => (new Ledger)->balances_grouped_by_type()
        ]);
    }

    public function fifo_grid_pdf(Request $request)
    {
        $cost_basis = (new App\Accounting\FIFO($request->currency_id))->get();
        $data = $cost_basis['data'];
        $pdf = PDF::loadview('partials.fifo-table',[
            'data' => $data,
            'currency_id' => $request->currency_id,
            'for_pdf' => true
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('UTF-8');
    }
}
