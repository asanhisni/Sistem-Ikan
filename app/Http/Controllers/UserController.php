<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cabang;

class UserController extends Controller
{
    /**
     * Pastikan hanya admin yang bisa akses.
     */
    private function adminOnly()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Daftar semua kepala cabang.
     */
    public function index()
    {
        $this->adminOnly();

        $users  = User::with('cabang')
                      ->where('role', 'kepala_cabang')
                      ->orderBy('name', 'asc')
                      ->get();
        $cabang = Cabang::orderBy('nama_cabang', 'asc')->get();

        // Admin yang merangkap (untuk ditampilkan di tabel)
        $adminUser = User::where('role', 'admin')->first();

        return view('users.index', compact('users', 'cabang', 'adminUser'));
    }

    /**
     * Form tambah kepala cabang.
     */
    public function create()
    {
        $this->adminOnly();

        // Cabang yang belum punya kepala cabang,
        // KECUALI cabang admin_merangkap (sudah diisi admin, tidak perlu kepala cabang baru)
        $cabangTersedia = Cabang::where('admin_merangkap', false)
            ->whereDoesntHave('users', function ($q) {
                $q->where('role', 'kepala_cabang');
            })->orderBy('nama_cabang')->get();

        return view('users.create', compact('cabangTersedia'));
    }

    /**
     * Simpan kepala cabang baru.
     */
    public function store(Request $request)
    {
        $this->adminOnly();

        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'cabang_id' => [
                'required',
                'exists:cabang,id',
                function ($attribute, $value, $fail) {
                    $cabang = Cabang::find($value);
                    // Cabang admin_merangkap tidak bisa diisi kepala cabang baru
                    if ($cabang && $cabang->admin_merangkap) {
                        $fail('Cabang ini dikelola langsung oleh admin (merangkap). Tidak bisa menambahkan kepala cabang.');
                        return;
                    }
                    $sudahAda = User::where('cabang_id', $value)
                                    ->where('role', 'kepala_cabang')
                                    ->exists();
                    if ($sudahAda) {
                        $fail('Cabang ini sudah memiliki kepala cabang.');
                    }
                },
            ],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'cabang_id.required' => 'Pilih cabang yang akan ditugaskan.',
            'cabang_id.exists'   => 'Cabang tidak ditemukan.',
        ]);

        User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'kepala_cabang',
            'cabang_id' => $request->cabang_id,
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'Kepala cabang berhasil ditambahkan!');
    }

    /**
     * Form edit kepala cabang.
     */
    public function edit(User $user)
    {
        $this->adminOnly();

        if ($user->role !== 'kepala_cabang') {
            abort(404);
        }

        // Cabang tersedia: bukan admin_merangkap, belum punya kepala cabang ATAU cabang user ini sendiri
        $cabangTersedia = Cabang::where('admin_merangkap', false)
            ->where(function ($q) use ($user) {
                $q->whereDoesntHave('users', function ($q2) {
                    $q2->where('role', 'kepala_cabang');
                })->orWhere('id', $user->cabang_id);
            })->orderBy('nama_cabang')->get();

        return view('users.edit', compact('user', 'cabangTersedia'));
    }

    /**
     * Update data kepala cabang.
     */
    public function update(Request $request, User $user)
    {
        $this->adminOnly();

        if ($user->role !== 'kepala_cabang') {
            abort(404);
        }

        $rules = [
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'cabang_id' => [
                'required',
                'exists:cabang,id',
                function ($attribute, $value, $fail) use ($user) {
                    $cabang = Cabang::find($value);
                    if ($cabang && $cabang->admin_merangkap) {
                        $fail('Cabang ini dikelola langsung oleh admin (merangkap). Tidak bisa menugaskan kepala cabang.');
                        return;
                    }
                    $sudahAda = User::where('cabang_id', $value)
                                    ->where('role', 'kepala_cabang')
                                    ->where('id', '!=', $user->id)
                                    ->exists();
                    if ($sudahAda) {
                        $fail('Cabang ini sudah memiliki kepala cabang.');
                    }
                },
            ],
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $request->validate($rules, [
            'name.required'      => 'Nama wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'cabang_id.required' => 'Pilih cabang yang akan ditugaskan.',
        ]);

        $data = [
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'cabang_id' => $request->cabang_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
                         ->with('success', 'Data kepala cabang berhasil diperbarui!');
    }

    /**
     * Hapus kepala cabang.
     */
    public function destroy(User $user)
    {
        $this->adminOnly();

        if ($user->role !== 'kepala_cabang') {
            abort(404);
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Kepala cabang berhasil dihapus!');
    }
}
