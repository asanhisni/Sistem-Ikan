<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ikan;
use App\Models\Cabang;
use App\Models\StokCabang;
use Illuminate\Support\Facades\Auth;

class IkanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ikan = Ikan::orderBy('nama_ikan', 'asc')->get();
        $namaCabang = null;

        if ($user && $user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi : Cabang::where('nama_cabang', $user->cabang ?? 'Cilawu')->first();
            if ($cabang) {
                $namaCabang = $cabang->nama_cabang;
                $stokMap = StokCabang::where('cabang_id', $cabang->id)
                    ->pluck('stok_kg', 'ikan_id')
                    ->toArray();

                foreach ($ikan as $i) {
                    $i->stok_kg = isset($stokMap[$i->id]) ? (float) $stokMap[$i->id] : 0.0;
                }
            }
        }

        return view('ikan.index', compact('ikan', 'namaCabang'));
    }

    public function create()
    {
        return view('ikan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'harga_per_kg' => 'required|numeric|min:0',
            'stok_kg' => 'required|numeric|min:0',
        ]);

        $ikan = Ikan::create($request->all());

        // Jika dibuat oleh kepala cabang, simpan stok spesifik ke cabang terkait
        $user = Auth::user();
        if ($user && $user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi : Cabang::where('nama_cabang', $user->cabang ?? 'Cilawu')->first();
            if ($cabang) {
                StokCabang::updateOrCreate(
                    ['cabang_id' => $cabang->id, 'ikan_id' => $ikan->id],
                    ['stok_kg' => $request->stok_kg]
                );
            }
        }

        return redirect()->route('ikan.index')->with('success', 'Ikan berhasil ditambahkan!');
    }

    public function edit(Ikan $ikan)
    {
        $user = Auth::user();
        $namaCabang = null;
        if ($user && $user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi : Cabang::where('nama_cabang', $user->cabang ?? 'Cilawu')->first();
            if ($cabang) {
                $namaCabang = $cabang->nama_cabang;
                $stokCabang = StokCabang::where('cabang_id', $cabang->id)
                    ->where('ikan_id', $ikan->id)
                    ->first();
                $ikan->stok_kg = $stokCabang ? (float) $stokCabang->stok_kg : 0.0;
            }
        }

        return view('ikan.edit', compact('ikan', 'namaCabang'));
    }

    public function update(Request $request, Ikan $ikan)
    {
        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'harga_per_kg' => 'required|numeric|min:0',
            'stok_kg' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        if ($user && $user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi : Cabang::where('nama_cabang', $user->cabang ?? 'Cilawu')->first();
            if ($cabang) {
                StokCabang::updateOrCreate(
                    ['cabang_id' => $cabang->id, 'ikan_id' => $ikan->id],
                    ['stok_kg' => $request->stok_kg]
                );
            }
            $ikan->update([
                'nama_ikan' => $request->nama_ikan,
                'harga_per_kg' => $request->harga_per_kg,
            ]);
        } else {
            $ikan->update($request->all());
        }

        return redirect()->route('ikan.index')->with('success', 'Data ikan & stok berhasil diubah!');
    }

    public function destroy(Ikan $ikan)
    {
        $ikan->delete();
        return redirect()->route('ikan.index')->with('success', 'Ikan berhasil dihapus!');
    }
}
