<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if (Auth::user()->role === 'dokter') {
                return redirect()->route('dokter.dashboard');
            }

            if (Auth::user()->role === 'pasien') {
                return redirect()->route('pasien.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if(User::where('no_ktp', $validated['no_ktp'])->exists()){
            return back()->withErrors([
                'no_ktp' => 'The provided KTP is already registered.',
            ]);
        }

        User::create([
            'name' => $validated['name'],
            'alamat' => $validated['alamat'],
            'no_ktp' => $validated['no_ktp'],
            'no_hp' => $validated['no_hp'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'pasien',
        ]);
        
        return redirect()->route('login')->with('success', 'User created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
