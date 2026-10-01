<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

    /**
     * Tampilkan halaman Lupa Password.
     */
    public function showForgotPassword(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgot-password');
    }

    /**
     * Verifikasi identitas user (Username/No HP + PIN Pemulihan).
     */
    public function verifyIdentity(Request $request)
    {
        $request->validate([
            'identifier'   => 'required|string',
            'recovery_pin' => 'required|string',
        ], [
            'identifier.required'   => 'Username atau No. HP akun wajib diisi.',
            'recovery_pin.required' => 'PIN Pemulihan Akun wajib diisi.',
        ]);

        $identifier = trim($request->identifier);
        $pin = trim($request->recovery_pin);

        // Cari user berdasarkan username, no HP, atau email
        $user = User::where('username', $identifier)
            ->orWhere('telepon', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user) {
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors(['identifier' => 'Akun dengan Username atau No. HP tersebut tidak ditemukan.']);
        }

        // Cek PIN Pemulihan
        if (empty($user->recovery_pin) || $user->recovery_pin !== $pin) {
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors(['recovery_pin' => 'PIN Pemulihan Akun yang Anda masukkan salah.']);
        }

        // Buat token sementara di session untuk otorisasi reset
        $resetToken = bin2hex(random_bytes(24));
        session([
            'password_reset_user_id' => $user->id,
            'password_reset_token'   => $resetToken,
        ]);

        return redirect()->route('password.request')
            ->with('step', 'reset')
            ->with('verified_user_name', $user->name)
            ->with('verified_user_username', $user->username)
            ->with('token', $resetToken);
    }

    /**
     * Simpan Password Baru setelah verifikasi berhasil.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string',
        ], [
            'password.required'     => 'Password baru wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password baru tidak cocok.',
            'password_confirmation.required' => 'Ulangi konfirmasi password baru.',
        ]);

        $sessionToken  = session('password_reset_token');
        $sessionUserId = session('password_reset_user_id');

        if (!$sessionToken || !$sessionUserId || $sessionToken !== $request->token) {
            return redirect()->route('password.request')
                ->withErrors(['identifier' => 'Sesi pemulihan telah kedaluwarsa. Silakan ulangi langkah pemulihan.']);
        }

        $user = User::find($sessionUserId);
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['identifier' => 'User tidak ditemukan.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token session
        session()->forget(['password_reset_user_id', 'password_reset_token']);

        return redirect()->route('login')
            ->with('success', 'Password akun (' . $user->username . ') berhasil diperbarui! Silakan masuk dengan password baru Anda.');
    }
}
