<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function __invoke(Request $request){
        $request->validate([
            'email_username' => 'required',
            'password' => 'required'
        ]);

        $fieldType = filter_var($request->email_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [
            $fieldType => $request->email_username,
            'password' => $request->password
        ];
        
        if(Auth::attempt($credentials)){
            return redirect()->intended('/admin');
        }else{
            return back()->withInput()->with('fail','wrong email or password');
        }
    }

    public function logout(){
        Auth::logout();
        return back();
    }
}
