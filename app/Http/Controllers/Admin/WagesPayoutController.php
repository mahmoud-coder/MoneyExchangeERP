<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\{COA, Option, Employee, WagesPayout, WagesPaid};
use Auth;
use DB;
use Exception;
use App;

class WagesPayoutController extends Controller
{

    public function index()
    {
        $this->authorize('create-expenses');
        return view('admin.accounting.salaries', [
            'main_menu' => $this->getAdminMenu(),
            'employees' => Employee::whereNull('left_at')->get(),
            'accounts' => json_encode([
                'wages' => COA::get_wages_account(),
                'payable_salary' => COA::get_payable_salary_account(),
                'payable_insurance' => COA::get_payable_insurance_account(),
                'payable_employees_tax' => COA::get_payable_employees_income_tax_account(),
                'cash' => COA::get_cash_account()
            ]),
            'equations' => Option::get('salary-tax-free-amount')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'incurred_at' => 'unique:wages_payouts'
        ],[
            'incurred_at.unique' => 'There are another wages payout with the same date'
        ]);
        try{
            DB::beginTransaction();
            $payout = WagesPayout::create($request->merge(['user_id' => Auth::id()])->except(['details']));
            $payout->journal_entry()->create([
                'date' => $request->incurred_at
            ])->details()->createMany([
                [
                    'account_id' => COA::get_wages_account()->id,
                    'amount' => $request->net_pay + $request->insurance_sum + $request->tax,
                    'type' => 'debit'
                ],
                [
                    'account_id' => COA::get_payable_salary_account()->id,
                    'amount' => $request->net_pay,
                    'type' => 'credit'
                ],
                [
                    'account_id' => COA::get_payable_insurance_account()->id,
                    'amount' => $request->insurance_sum,
                    'type' => 'credit'
                ],
                [
                    'account_id' => COA::get_payable_employees_income_tax_account()->id,
                    'amount' => $request->tax,
                    'type' => 'credit'
                ]
            ]);
            $payout->details()->createMany($request->details['employees']);
            DB::commit();
            return ['success'=> true, 'message' => "The wages payout incurred at $request->incurred_at, has been created"];
        }catch(Exception $e){
            DB::rollBack();
            return ['success'=> false, 'message' => $e->getMessage()];
        }
    }

    public function pay(Request $request, WagesPayout $payout)
    {
        try{
            DB::beginTransaction();
            $paid = $payout->paids()->create([
                'paid_at' => $request->date
            ]);
            $paid->journal_entry()->create([
                'date' =>  $request->date
            ])->details()->createMany([
                [
                    'account_id' => COA::get_payable_salary_account()->id,
                    'amount' => $request->net_pay,
                    'type' => 'debit'
                ],
                [
                    'account_id' => COA::get_payable_insurance_account()->id,
                    'amount' => $request->all_insurance,
                    'type' => 'debit'
                ],
                [
                    'account_id' => COA::get_payable_employees_income_tax_account()->id,
                    'amount' => $request->tax,
                    'type' => 'debit'
                ],
                [
                    'account_id' => COA::get_cash_account()->id,
                    'amount' => $request->net_pay + $request->all_insurance + $request->tax,
                    'type' => 'credit'
                ]
            ]);
            $payout->details->whereIn('id', $request->choosed_employees)->each( function($wd) use($paid){
                $wd->wages_paid_id = $paid->id;
                $wd->save();
            } );
            DB::commit();
            return ['success'=> true, 'message' => 'created'];
        }catch(Exception $e){
            DB::rollBack();
            return ['success'=> false, 'message' => $e->getMessage()];
        }
    }

    public function destroy(WagesPayout $payout)
    {
        $this->authorize('create-expenses');
        try{
            $payout->delete();
            return ['success'=> true, 'message'=> "Wages incurred in $payout->incurred_at, has been deleted"];
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function pdf(WagesPayout $payout, Request $request){
        App::setLocale($request->language);
        $pdf = PDF::loadview('admin.accounting.salaries-pdf', [
            'payout' => $payout
        ])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function paid_wages_pdf(WagesPaid $wages_paid, Request $request)
    {
        return $this->pdf($wages_paid->payout, $request);
    }
}
