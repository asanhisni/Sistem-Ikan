<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Ikan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $user = Auth::user();
        $query = Transaksi::with(['pelanggan', 'ikan', 'cabangRelasi'])->orderBy('tanggal_transaksi', 'desc');

        if ($user->role === 'kepala_cabang') {
            if ($user->cabang_id) {
                $query->where('cabang_id', $user->cabang_id);
            } elseif ($user->cabang) {
                $query->where('cabang', $user->cabang);
            }
        }

        if ($search) {
            $query->where('pembeli_nama', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $transaksi = $query->paginate(10);
        return view('transaksi.index', compact('transaksi', 'search', 'status'));
    }


    public function create()
    {
        $ikan = Ikan::all();
        $pelanggan = Pelanggan::all();
        return view('transaksi.create', compact('ikan', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembeli_nama' => 'required|string|max:255',
            'ikan_id' => 'required|exists:ikan,id',
            'jumlah_kg' => 'required|numeric|min:0.1',
            'status' => 'required|in:lunas,belum_lunas,dp',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
        ], [
            'pembeli_nama.required' => 'Nama Pembeli harus diisi.',
            'ikan_id.required' => 'Pilih jenis ikan.',
            'jumlah_kg.required' => 'Jumlah (Kg) harus diisi.',
        ]);

        $user = Auth::user();
        $userCabangNama = $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : ($user->cabang ?? 'Cilawu');
        $userCabangId = $user->cabang_id;

        $ikan = Ikan::findOrFail($request->ikan_id);
        $total = $ikan->harga_per_kg * $request->jumlah_kg;
        
        $jumlahDibayar = 0;
        if ($request->status === 'lunas') {
            $jumlahDibayar = $total;
        } elseif ($request->status === 'dp') {
            $jumlahDibayar = $request->jumlah_dibayar ?? 0;
        }

        $transaksi = Transaksi::create([
            'pembeli_nama' => $request->pembeli_nama,
            'ikan_id' => $request->ikan_id,
            'jumlah_kg' => $request->jumlah_kg,
            'harga_per_kg' => $ikan->harga_per_kg,
            'total' => $total,
            'status' => $request->status,
            'jumlah_dibayar' => $jumlahDibayar,
            'tanggal_transaksi' => Carbon::now(),
            'cabang' => $userCabangNama,
            'cabang_id' => $userCabangId,
            'keterangan' => $request->keterangan,
        ]);
        
            // Redirect kembali ke daftar transaksi dengan notifikasi sukses
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function edit(Transaksi $transaksi)
    {
        $ikan = Ikan::all();
        return view('transaksi.edit', compact('transaksi', 'ikan'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'pembeli_nama' => 'required|string|max:255',
            'ikan_id' => 'required|exists:ikan,id',
            'jumlah_kg' => 'required|numeric|min:0.1',
            'status' => 'required|in:lunas,belum_lunas,dp',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
        ]);

        $ikan = Ikan::findOrFail($request->ikan_id);
        $total = $ikan->harga_per_kg * $request->jumlah_kg;
        
        $jumlahDibayar = 0;
        if ($request->status === 'lunas') {
            $jumlahDibayar = $total;
        } elseif ($request->status === 'dp') {
            $jumlahDibayar = $request->jumlah_dibayar ?? 0;
        }

        $transaksi->update([
            'pembeli_nama' => $request->pembeli_nama,
            'ikan_id' => $request->ikan_id,
            'jumlah_kg' => $request->jumlah_kg,
            'harga_per_kg' => $ikan->harga_per_kg,
            'total' => $total,
            'status' => $request->status,
            'jumlah_dibayar' => $jumlahDibayar,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diubah!');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
