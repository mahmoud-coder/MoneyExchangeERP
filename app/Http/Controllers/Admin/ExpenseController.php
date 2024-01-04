<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-expenses');
        return view('admin.expenses.index', [
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
        $this->authorize('create-expenses');
        return view('admin.expenses.create', [
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
        $this->authorize('create-expenses');
        $request->validate([
            'amount' => 'required'
        ]);
        $expense = new Expense;
        $expense->user_id = Auth::id();
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        $expense->desc = $request->desc;
        $expense->periodic_status = $request->periodic_status;
        if($request->periodic_status == 0){
            $expense->date = $request->date;
        }else{
            $expense->from = $request->from ?: date('Y-m-d');
            $expense->to = $request->to;
        }
        $expense->save();
        return ['success' => true];
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
        $this->authorize('create-expenses');
        $expense = Expense::find($id);
        return view('admin.expenses.create', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'edit',
            'expense' => $expense
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
        $this->authorize('create-expenses');
        $request->validate([
            'amount' => 'required'
        ]);
        $expense = Expense::find($id);
        $expense->update($request->all());
        return ['success' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('create-expenses');
        Expense::find($id)->delete();
        return ['success' => true];
    }
}
