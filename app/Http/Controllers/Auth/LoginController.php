<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function username()
    {
        return 'username';
    }

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        Auth::logout();
        session(['actingUserId' => null]);
        session(['actingUserName' => null]);
        session(['adminRights' => null]);
        session(['customerSelectQuery'=>null]);
        return redirect('/login');
    }

    public function authenticated(Request $request){
        session(['actingUserId' => Auth::id()]);
        session(['actingUserName' => Auth::user()->name]);
        session(['adminRights' => Auth::user()->adminRights]);
        session(['customerSelectQuery' => Auth::user()->customerSelectQuery]);

        $visibleLobs=(json_decode(Auth::user()->extraProperties,true))['visibleLobs'];
        if (!isset($visibleLobs))
            $visibleLobs=array();
        session(['visibleLobs'=> $visibleLobs]);
    }
}
