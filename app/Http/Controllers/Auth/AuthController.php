<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
