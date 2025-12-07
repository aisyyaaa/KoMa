<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerVerificationController extends Controller
{
    public function show()
    {
        return view('seller.auth.verify');
    }

    public function verify(Request $request)
    {
        // implement verification logic (token/email)
        return redirect()->route('seller.dashboard');
    }

    public function resend(Request $request)
    {
        // implement resend verification
        return back()->with('status', 'Verification sent');
    }
}
