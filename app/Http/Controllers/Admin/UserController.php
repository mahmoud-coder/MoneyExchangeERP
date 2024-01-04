<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Hash;
use Session;
use Auth;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index',[
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
        $this->authorize('create-users');
        return view('admin.users.create',[
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
        $this->authorize('create-users');
        $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'role' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        Session::flash('success', "$request->name user has been created");
        return back()->withInput();
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
    public function edit(user $user)
    {
        $this->authorize('create-users');
        return view('admin.users.create', [
            'main_menu' => $this->getAdminMenu(),
            'type' => 'edit',
            'user' => $user
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
        $this->authorize('create-users');
        $request->validate([
            'name' =>Rule::unique('users')->ignore($id),
            'email' => Rule::unique('users')->ignore($id)
        ]);
        $user = User::find($id);
        if($request->has('name')){
            $user->name = $request->name;
        }
        if($request->has('email')){
           $user->email = $request->email;
        }
        $user->role = $request->role;
        $user->save();
        Session::flash('success', 'The User has been successfully edited');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('create-users');
        try{
            $user->delete();
            return ['success' => true];
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show_profile(){
        return view('admin.users.profile', [
            'main_menu' => $this->getAdminMenu()
        ]);
    }
    public function profile_edit(Request $request){
        $me = Auth::user();
        $request->validate([
            'name'   => Rule::unique('users')->ignore( $me->id ),
            'email'  => Rule::unique('users')->ignore( $me->id ),
            'avatar' => 'mimes:jpg,bmp,png,gif'
        ]);
        if($request->has('name')){
            $me->name = $request->name;
        }
        if($request->has('email')){
            $me->email = $request->email;
        }
        if($request->has('avatar')){
            $avatar = $request->file('avatar');
            $path = $avatar->store('public/avatars');
            if($me->avatar_src && file_exists( $old_avatar = storage_path('app/public/avatars/' . $me->avatar_src) )){
                unlink($old_avatar);
            }
            $me->avatar_src = substr($path, 15);
        }
        $me->save();
        Session::flash('success', 'Your Profile has been updated');
        return back()->withInput();
    }

    public function profile_changepassword(Request $request){
        $request->validate([
            'current_password' => 'password',
            'new_password' => 'required|min:6|confirmed'
        ]);
        $me = Auth::user();
        $me->password = Hash::make($request->new_password);
        $me->save();
        Session::flash('success', 'Your password has been changed');
        return back()->withInput();
    }
}
