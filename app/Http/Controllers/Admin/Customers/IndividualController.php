<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\IndividualCustomer;
use Exception;
use DB;
use Auth;

class IndividualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-transactions');
        return view("admin.customers.individual", [
            'main_menu' => $this->getAdminMenu(),
            'countries' => $this->getCountries()
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
        $this->authorize('create-transactions');
        $request->validate([
            'name' => 'required',
            'surname'=> 'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'country_id' => 'required',
            'id_card' => 'required',
            'address' => 'required',
        ]);

        try{
            DB::beginTransaction();
            $customer = new Customer;
            $customer->creator_type = User::class;
            $customer->creator_id = Auth::id();
            IndividualCustomer::create($request->all())->customer()->save($customer);
            DB::commit();
            return ['success' => true, 'message' => "the new customer has been added", 'customer_id' => $customer->id];
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
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
        //
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
        $this->authorize('create-transactions');
        $request->validate([
            'name' => 'required',
            'surname'=> 'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'country_id' => 'required',
            'id_card' => 'required',
            'address' => 'required',
        ]);
        $customer = IndividualCustomer::find($id);
        try{
            $customer->update($request->all());
            return ['success' => true, 'message' => "the MBCT$customer->id customer has been updated"];
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
