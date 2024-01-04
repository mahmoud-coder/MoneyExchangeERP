<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Database\QueryException;

class EmployeeController extends Controller
{
    private $validations = [
        'name' => 'required|min:2',
        'surname'=> 'required|min:2',
        'duty' => 'required',
        'salary' => 'required|numeric|max:9999.99',
        'pension' => 'nullable|numeric|max:99.99',
        'social_insurance' => 'required|numeric|max:99.99',
        'joined_at' => 'required|date'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-employees');
        return view('admin.employees.index', [
            'main_menu' => $this->getAdminMenu(),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-employees');
        return view('admin.employees.create', [
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
        $this->authorize('create-employees');
        $request->validate($this->validations);
        Employee::create($request->all());
        return ['message' => "$request->name $request->surname has added successfully"];
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
        $this->authorize('create-employees');
        return view('admin.employees.create', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'edit',
            'employee' => Employee::find($id)
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
        $this->authorize('create-employees');
        $request->validate($this->validations);
        Employee::find($id)->update($request->all());
        return ['message'=> "$request->name has been updated"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('create-employees');
        try{
            $employee->delete();
            return ['success'=> true, 'message'=> "$employee->name has been deleted"];
        }catch(QueryException $e){
            return response()->json(['success' => false, 'message' => "you can't delete <b>$employee->name</b>, because he(she) has operations <hr>You can set a left date for him(here) on Edit employee" ], 400);
        }
    }
}
