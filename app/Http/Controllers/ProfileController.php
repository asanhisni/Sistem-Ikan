<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman detail profil user yang sedang login.
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('cabangRelasi');
        return view('profile.show', compact('user'));
    }

    /**
     * Update data profil (Nama, Username, Email, No. HP, PIN Pemulihan, & Password).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'telepon'      => 'nullable|string|max:20',
            'recovery_pin' => 'required|string|max:20',
        ];

        // Jika user ingin mengganti password
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password']         = 'required|string|min:6|confirmed';
        }

        $request->validate($rules, [
            'name.required'             => 'Nama lengkap wajib diisi.',
            'username.required'         => 'Username wajib diisi.',
            'username.unique'           => 'Username sudah digunakan oleh akun lain.',
            'email.required'            => 'Email wajib diisi.',
            'email.unique'              => 'Email sudah terdaftar pada akun lain.',
            'recovery_pin.required'     => 'PIN Pemulihan Akun wajib diisi.',
            'current_password.required' => 'Password saat ini wajib diisi untuk verifikasi perubahan password.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Validasi password saat ini jika ganti password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withInput()
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name         = $request->name;
        $user->username     = $request->username;
        $user->email        = $request->email;
        $user->telepon      = $request->telepon;
        $user->recovery_pin = $request->recovery_pin;
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profil akun Anda berhasil diperbarui!');
    }
}
