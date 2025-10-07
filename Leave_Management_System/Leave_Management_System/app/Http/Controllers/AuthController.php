<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }
  public function edit()
{
    $user = auth()->user();
    return view('settings.edit', compact('user'));
}



public function update(Request $request)
{
    $user = auth()->user();

    $data = $request->validate([
        'first_name' => 'required|string|max:120',
        'last_name' => 'required|string|max:120',
        'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'profile_picture' => 'nullable|image|max:2048',  // max 2MB
    ]);

    if (!empty($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
    }

    // Update other fields
    $user->first_name = $data['first_name'];
    $user->last_name = $data['last_name'];
    $user->email = $data['email'];

    $user->save();

    return redirect()->route('settings.edit')->with('success', 'Profile updated successfully.');
}


 public function login(Request $request)
{
    $request->validate([
        'user_type' => 'required|in:admin,manager,employee',
        'email_or_number' => 'required|string',
        'password' => 'required|string',
    ]);

    $type = $request->input('user_type');
    $inputId = $request->input('email_or_number');
    $password = $request->input('password');
    $remember = $request->has('remember');

    if ($type === 'admin') {
    if ($inputId === 'ADMIN2025' && $password === 'Admin@1234') {
        Auth::loginUsingId(10, $remember);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    } else {
        return back()->withErrors(['email_or_number' => 'Admin number or password incorrect'])->withInput();
    }
}



    // For manager and employee use normal Laravel auth check
    $user = \App\Models\User::where('email', $inputId)->where('role', $type)->first();

    if (!$user) {
        return back()->withErrors([
            'email_or_number' => 'No user found with this email and role combination.'
        ])->onlyInput('email_or_number');
    }

    if (Auth::attempt(['email' => $inputId, 'password' => $password], $remember)) {
        $request->session()->regenerate();

        if ($type === 'manager') {
            return redirect()->route('manager.dashboard');
        }

        return redirect()->route('dashboard'); // employee
    }

    return back()->withErrors([
        'email_or_number' => 'The provided credentials do not match our records.',
    ])->onlyInput('email_or_number');
}




    // Show dashboard to authenticated users
    public function dashboard()
    {
        return view('dashboard');
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
