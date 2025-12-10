<?php

namespace App\Http\Controllers\Platform\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PlatformAuthController extends Controller
{
    public function showLogin()
    {
        return view('platform.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // allow only users flagged as platform admin
        $user = User::where('email', $data['email'])->first();
        if (!$user || !$user->is_platform_admin) {
            return back()->withErrors(['email' => 'Akun tidak memiliki akses platform atau tidak ditemukan'])->withInput();
        }

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $request->session()->regenerate();
            return redirect()->intended(route('platform.dashboard'));
        }

        return back()->withErrors(['email' => 'Credensial tidak cocok'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('platform.auth.login');
    }
}
