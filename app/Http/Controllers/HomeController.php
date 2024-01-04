<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
   public function index(){
    return view("home.index");
   }

   public function logIn(){
    return view("home.login");
   }

   public function Connexion(Request $request){
        request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $check = $request->only('username','password');
        $remember = ($request->input('remember') == 'on')? true:false;

        if(Auth::guard('admin')->attempt($check, $remember)){

            if(Auth::guard('admin')->user()->status =='Inactif'){
                Auth::guard('admin')->logout();
                return redirect()->back()->with([
                    'message'=>'Your account is not active',
                    'error'=>true
                ]);
            }

            return redirect('/dashboard/index')->with([
                'message'=>' Bienvenue M : '. Auth::guard('admin')->user()->first_name,
                'error'=>false
            ]);

        }else{
            return redirect()->back()->withInput($request->only('username','password'))->withErrors([
                'username'=>'Invalid User name OR password',
                'password'=>'Invalid User name OR password'
            ]);
        }
   }

   public function logout(){
    Auth::guard('admin')->logout();
    return redirect('login')->with([
        'message'=>'Your are loggout successfully',
        'error'=>true
    ]);
   }
}
