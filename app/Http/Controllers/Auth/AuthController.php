<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman pilihan login/daftar untuk tipe akun.
     */
    public function showLoginSelection()
    {
        // Use the existing seller login form as the default shared login form
        return view('seller.auth.login');
    }

    /**
     * Tampilkan form pendaftaran pembeli minimal.
     */
    public function showBuyerRegister()
    {
        return view('auth.buyer-register');
    }

    /**
     * Handle shared login for platform, seller, and buyer.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $data['email'];
        $password = $data['password'];

        // 1) Platform admin (users table with is_platform_admin)
        $user = User::where('email', $email)->first();
        if ($user && $user->is_platform_admin) {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->intended(route('platform.dashboard'));
            }
            return back()->withErrors(['email' => 'Credensial tidak cocok untuk akun platform'])->withInput();
        }

        // 2) Seller (match pic_email)
        $seller = Seller::where('pic_email', $email)->first();
        if ($seller) {
            if (Hash::check($password, $seller->password)) {
                Auth::login($seller);
                $request->session()->regenerate();
                return redirect()->intended(route('seller.dashboard'));
            }
            return back()->withErrors(['email' => 'Credensial tidak cocok untuk akun penjual'])->withInput();
        }

        // 3) Regular user (buyer)
        if ($user) {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->intended(route('landingpage.index'));
            }
            return back()->withErrors(['email' => 'Credensial tidak cocok untuk akun pengguna'])->withInput();
        }

        return back()->withErrors(['email' => 'Akun tidak ditemukan'])->withInput();
    }

    /**
     * Tangani pendaftaran pembeli minimal.
     */
    public function registerBuyer(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        $password = Str::random(12);

        $user = new User();
        $user->name = $data['name'];
        // If the users table has a phone column
        if (property_exists($user, 'phone') || in_array('phone', $user->getFillable())) {
            $user->phone = $data['phone'];
        } else {
            // Try setting via attribute regardless; if column doesn't exist DB will throw later
            $user->phone = $data['phone'];
        }
        $user->email = $data['email'];
        $user->password = Hash::make($password);
        $user->save();

        return redirect()->route('login')->with('status', "Akun pembeli dibuat. Silakan masuk. (Password auto-generated)");
    }
}
