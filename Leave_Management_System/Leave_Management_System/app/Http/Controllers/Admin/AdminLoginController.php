<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'admin_number' => ['required', 'string'],
            'admin_password' => ['required', 'string'],
        ]);

        // Hardcoded credentials for quick setup; replace with config or env variables
        if ($request->admin_number === 'ADMIN2025' && $request->admin_password === 'Admin@1234') {
            Auth::guard('web')->loginUsingId(1); // assumes user with ID 1 is admin
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'admin_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('admin_number');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
