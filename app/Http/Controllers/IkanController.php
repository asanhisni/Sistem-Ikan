<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ikan;

class IkanController extends Controller
{
    public function index()
    {
        $ikan = Ikan::orderBy('nama_ikan', 'asc')->get();
        return view('ikan.index', compact('ikan'));
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

        Ikan::create($request->all());

        return redirect()->route('ikan.index')->with('success', 'Ikan berhasil ditambahkan!');
    }

    public function edit(Ikan $ikan)
    {
        return view('ikan.edit', compact('ikan'));
    }

    public function update(Request $request, Ikan $ikan)
    {
        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'harga_per_kg' => 'required|numeric|min:0',
            'stok_kg' => 'required|numeric|min:0',
        ]);

        $ikan->update($request->all());

        return redirect()->route('ikan.index')->with('success', 'Data ikan berhasil diubah!');
    }

    public function destroy(Ikan $ikan)
    {
        $ikan->delete();
        return redirect()->route('ikan.index')->with('success', 'Ikan berhasil dihapus!');
    }
}
