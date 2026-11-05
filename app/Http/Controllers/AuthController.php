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

        // Restore sesi impersonasi dari database (agar tombol 'Kembali ke Admin'
        // tetap muncul meski session sebelumnya sudah habis/logout)
        $loggedUser = Auth::user();
        if ($loggedUser && $loggedUser->impersonated_by_admin_id) {
            $request->session()->put('impersonator_admin_id', $loggedUser->impersonated_by_admin_id);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
    }

    /**
     * Beralih (Impersonate) langsung menjadi Kepala Cabang Suci.
     */
    public function switchCabangSuci(Request $request)
    {
        try {
            $currentUser = Auth::user();
            if (!$currentUser || $currentUser->role !== 'admin') {
                return redirect()->route('dashboard')->with('error', 'Hanya Admin yang dapat beralih akun.');
            }

            // Simpan id admin asli SEBELUM session diubah
            $adminId = $currentUser->id;

            // Cari atau buat Cabang Suci
            $suciCabang = \App\Models\Cabang::firstOrCreate(
                ['nama_cabang' => 'Suci'],
                [
                    'kode_cabang'     => 'SCI-01',
                    'lokasi'          => 'Jl. Suci',
                    'admin_merangkap' => false,
                ]
            );

            // Cari akun Kepala Cabang Suci
            $suciUser = User::where('username', 'kcsuci')
                ->orWhere(function ($q) use ($suciCabang) {
                    $q->where('cabang_id', $suciCabang->id)->where('role', 'kepala_cabang');
                })
                ->first();

            // Jika belum ada di database, buat akunnya
            if (!$suciUser) {
                $suciUser = User::create([
                    'name'         => 'Kepala Cabang Suci',
                    'username'     => 'kcsuci',
                    'email'        => 'kcsuci@sistemikan.com',
                    'password'     => Hash::make('suci123'),
                    'role'         => 'kepala_cabang',
                    'cabang_id'    => $suciCabang->id,
                    'recovery_pin' => '123456',
                ]);
            } else {
                if ($suciUser->role !== 'kepala_cabang' || $suciUser->cabang_id !== $suciCabang->id) {
                    $suciUser->update([
                        'role'      => 'kepala_cabang',
                        'cabang_id' => $suciCabang->id,
                    ]);
                }
            }

            $suciUserName = $suciUser->name;

            // Simpan flag impersonasi di DB akun Suci
            try {
                $suciUser->impersonated_by_admin_id = $adminId;
                $suciUser->save();
            } catch (\Throwable $th) {
                \Log::warning('Gagal menyimpan impersonated_by_admin_id ke DB: ' . $th->getMessage());
            }

            // Login sebagai Kepala Cabang Suci
            Auth::loginUsingId($suciUser->id, true);

            // Simpan id admin asli di session
            $request->session()->put('impersonator_admin_id', $adminId);

            return redirect()->route('dashboard')
                ->with('success', 'Berhasil beralih menjadi Kepala Cabang Suci (' . $suciUserName . ').');
        } catch (\Throwable $e) {
            \Log::error('Error switchCabangSuci: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('dashboard')
                ->with('error', 'Gagal beralih ke Cabang Suci: ' . $e->getMessage());
        }
    }

    /**
     * Kembali ke akun Admin Utama.
     */
    public function switchBackAdmin(Request $request)
    {
        try {
            $currentUser = Auth::user();
            $adminId = $request->session()->get('impersonator_admin_id') 
                ?? ($currentUser ? $currentUser->impersonated_by_admin_id : null);

            if (!$adminId) {
                return redirect()->route('dashboard')->with('error', 'Sesi admin tidak ditemukan.');
            }

            $adminUser = User::find($adminId);
            if (!$adminUser || $adminUser->role !== 'admin') {
                return redirect()->route('dashboard')->with('error', 'Akun Admin tidak valid.');
            }

            // Hapus flag impersonasi dari database (di akun Suci yang sedang aktif)
            if ($currentUser) {
                try {
                    $currentUser->impersonated_by_admin_id = null;
                    $currentUser->save();
                } catch (\Throwable $th) {
                    \Log::warning('Gagal menghapus impersonated_by_admin_id di DB: ' . $th->getMessage());
                }
            }

            // Login kembali sebagai admin
            Auth::loginUsingId($adminUser->id, true);

            // Hapus flag impersonasi dari session
            $request->session()->forget('impersonator_admin_id');

            return redirect()->route('dashboard')
                ->with('success', 'Kembali ke mode Administrator Utama.');
        } catch (\Throwable $e) {
            \Log::error('Error switchBackAdmin: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Gagal kembali ke Admin: ' . $e->getMessage());
        }
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        // Sengaja TIDAK menghapus impersonated_by_admin_id dari database saat logout,
        // agar saat login ulang (via remember token / manual), sesi impersonasi
        // dapat dipulihkan otomatis dan tombol "Kembali ke Admin" tetap muncul.
        // Flag DB akan dihapus hanya saat switchBackAdmin() dipanggil.
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
