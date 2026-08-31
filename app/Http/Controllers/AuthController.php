<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Coba login dengan username
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Cek juga dengan email jika username tidak cocok
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Coba dengan email
            $credentialsEmail = [
                'email'    => $request->username,
                'password' => $request->password,
            ];
            if (!Auth::attempt($credentialsEmail, $request->boolean('remember'))) {
                return back()
                    ->withInput($request->only('username'))
                    ->withErrors(['username' => 'Username atau password salah.']);
            }
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
