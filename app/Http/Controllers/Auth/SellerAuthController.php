<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAuthController extends Controller
{
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        // implement registration logic
        return redirect()->route('seller.dashboard');
    }

    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        // implement login logic
        return redirect()->route('seller.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
