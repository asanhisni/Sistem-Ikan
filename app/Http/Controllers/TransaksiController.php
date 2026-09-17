<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Ikan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $user = Auth::user();
        $query = Transaksi::with(['pelanggan', 'ikan', 'cabangRelasi', 'details.ikan'])->orderBy('tanggal_transaksi', 'desc');

        if ($user->role === 'kepala_cabang') {
            if ($user->cabang_id) {
                $query->where('cabang_id', $user->cabang_id);
            } elseif ($user->cabang) {
                $query->where('cabang', $user->cabang);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pembeli_nama', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($qp) use ($search) {
                      $qp->where('telepon', 'like', "%{$search}%")
                         ->orWhere('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            if ($status === 'belum_lunas') {
                // DP juga termasuk belum lunas
                $query->whereIn('status', ['belum_lunas', 'dp']);
            } else {
                $query->where('status', $status);
            }
        }

        $transaksi = $query->paginate(10)->withQueryString();
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
            'items' => 'required|array|min:1',
            'items.*.ikan_id' => 'required|exists:ikan,id',
            'items.*.jumlah_kg' => 'required|numeric|min:0.1',
            'status' => 'required|in:lunas,belum_lunas,dp',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
        ], [
            'pembeli_nama.required' => 'Nama Pembeli harus diisi.',
            'items.required' => 'Minimal pilih 1 jenis ikan.',
            'items.min' => 'Minimal pilih 1 jenis ikan.',
            'items.*.ikan_id.required' => 'Jenis ikan harus dipilih.',
            'items.*.jumlah_kg.required' => 'Jumlah (Kg) harus diisi.',
            'items.*.jumlah_kg.min' => 'Jumlah (Kg) minimal 0.1.',
        ]);

        $user = Auth::user();
        $userCabangNama = $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : ($user->cabang ?? 'Cilawu');
        $userCabangId = $user->cabang_id;

        return DB::transaction(function () use ($request, $userCabangNama, $userCabangId) {
            $total = 0;
            $totalKg = 0;
            $detailsData = [];
            $firstIkanId = null;

            foreach ($request->items as $item) {
                $ikanModel = Ikan::findOrFail($item['ikan_id']);
                $kg = (float) $item['jumlah_kg'];
                $subtotal = $ikanModel->harga_per_kg * $kg;
                $total += $subtotal;
                $totalKg += $kg;

                if (!$firstIkanId) {
                    $firstIkanId = $ikanModel->id;
                }

                $detailsData[] = [
                    'ikan_id' => $ikanModel->id,
                    'jumlah_kg' => $kg,
                    'harga_per_kg' => $ikanModel->harga_per_kg,
                    'subtotal' => $subtotal,
                ];

                // ─── OTOMATIS KURANGI STOK IKAN ───
                $ikanModel->decrement('stok_kg', $kg);
            }

            $jumlahDibayar = 0;
            if ($request->status === 'lunas') {
                $jumlahDibayar = $total;
            } elseif ($request->status === 'dp') {
                $jumlahDibayar = $request->jumlah_dibayar ?? 0;
            }

            // Cari atau buat Pelanggan otomatis (utamakan berdasarkan no HP)
            $pelangganId = null;
            $telepon = $request->telepon ? trim($request->telepon) : null;
            $namaPembeli = trim($request->pembeli_nama);

            if ($telepon) {
                $pelanggan = Pelanggan::where('telepon', $telepon)->first();
                if (!$pelanggan) {
                    $pelanggan = Pelanggan::create([
                        'nama' => $namaPembeli,
                        'telepon' => $telepon,
                        'cabang' => $userCabangNama,
                    ]);
                }
                $pelangganId = $pelanggan->id;
            } else {
                $pelanggan = Pelanggan::where('nama', $namaPembeli)->first();
                if ($pelanggan) {
                    $pelangganId = $pelanggan->id;
                }
            }

            $transaksi = Transaksi::create([
                'pelanggan_id' => $pelangganId,
                'pembeli_nama' => $namaPembeli,
                'telepon' => $telepon,
                'ikan_id' => $firstIkanId,
                'jumlah_kg' => $totalKg,
                'harga_per_kg' => count($detailsData) === 1 ? $detailsData[0]['harga_per_kg'] : 0,
                'total' => $total,
                'status' => $request->status,
                'jumlah_dibayar' => $jumlahDibayar,
                'tanggal_transaksi' => Carbon::now(),
                'cabang' => $userCabangNama,
                'cabang_id' => $userCabangId,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($detailsData as $detail) {
                $transaksi->details()->create($detail);
            }

            return redirect()->route('transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil disimpan dan stok ikan otomatis terpotong!');
        });
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['details.ikan', 'ikan', 'pelanggan', 'cabangRelasi']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('details.ikan', 'ikan');
        $ikan = Ikan::all();
        $pelanggan = Pelanggan::all();
        return view('transaksi.edit', compact('transaksi', 'ikan', 'pelanggan'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'pembeli_nama' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.ikan_id' => 'required|exists:ikan,id',
            'items.*.jumlah_kg' => 'required|numeric|min:0.1',
            'status' => 'required|in:lunas,belum_lunas,dp',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
        ], [
            'pembeli_nama.required' => 'Nama Pembeli harus diisi.',
            'items.required' => 'Minimal pilih 1 jenis ikan.',
            'items.*.ikan_id.required' => 'Jenis ikan harus dipilih.',
            'items.*.jumlah_kg.required' => 'Jumlah (Kg) harus diisi.',
        ]);

        return DB::transaction(function () use ($request, $transaksi) {
            // ─── 1. KEMBALIKAN STOK LAMA TERLEBIH DAHULU ───
            if ($transaksi->details && $transaksi->details->count() > 0) {
                foreach ($transaksi->details as $oldDetail) {
                    if ($oldDetail->ikan_id) {
                        $oldIkan = Ikan::find($oldDetail->ikan_id);
                        if ($oldIkan) {
                            $oldIkan->increment('stok_kg', (float) $oldDetail->jumlah_kg);
                        }
                    }
                }
            } elseif ($transaksi->ikan_id) {
                $oldIkan = Ikan::find($transaksi->ikan_id);
                if ($oldIkan) {
                    $oldIkan->increment('stok_kg', (float) $transaksi->jumlah_kg);
                }
            }

            // ─── 2. HITUNG & POTONG STOK BARU ───
            $total = 0;
            $totalKg = 0;
            $detailsData = [];
            $firstIkanId = null;

            foreach ($request->items as $item) {
                $ikanModel = Ikan::findOrFail($item['ikan_id']);
                $kg = (float) $item['jumlah_kg'];
                $subtotal = $ikanModel->harga_per_kg * $kg;
                $total += $subtotal;
                $totalKg += $kg;

                if (!$firstIkanId) {
                    $firstIkanId = $ikanModel->id;
                }

                $detailsData[] = [
                    'ikan_id' => $ikanModel->id,
                    'jumlah_kg' => $kg,
                    'harga_per_kg' => $ikanModel->harga_per_kg,
                    'subtotal' => $subtotal,
                ];

                // Kurangi stok ikan yang baru diinput
                $ikanModel->decrement('stok_kg', $kg);
            }

            $jumlahDibayar = 0;
            if ($request->status === 'lunas') {
                $jumlahDibayar = $total;
            } elseif ($request->status === 'dp') {
                $jumlahDibayar = $request->jumlah_dibayar ?? 0;
            }

            $pelangganId = $transaksi->pelanggan_id;
            $telepon = $request->telepon ? trim($request->telepon) : null;
            $namaPembeli = trim($request->pembeli_nama);

            if ($telepon) {
                $pelanggan = Pelanggan::where('telepon', $telepon)->first();
                if (!$pelanggan) {
                    $pelanggan = Pelanggan::create([
                        'nama' => $namaPembeli,
                        'telepon' => $telepon,
                        'cabang' => $transaksi->cabang ?? 'Pusat',
                    ]);
                }
                $pelangganId = $pelanggan->id;
            }

            $transaksi->update([
                'pelanggan_id' => $pelangganId,
                'pembeli_nama' => $namaPembeli,
                'telepon' => $telepon,
                'ikan_id' => $firstIkanId,
                'jumlah_kg' => $totalKg,
                'harga_per_kg' => count($detailsData) === 1 ? $detailsData[0]['harga_per_kg'] : 0,
                'total' => $total,
                'status' => $request->status,
                'jumlah_dibayar' => $jumlahDibayar,
                'keterangan' => $request->keterangan,
            ]);

            // Hapus detail lama dan masukkan yang baru
            $transaksi->details()->delete();
            foreach ($detailsData as $detail) {
                $transaksi->details()->create($detail);
            }

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diubah dan stok ikan diperbarui!');
        });
    }

    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            // ─── KEMBALIKAN STOK JIKA TRANSAKSI DIHAPUS ───
            if ($transaksi->details && $transaksi->details->count() > 0) {
                foreach ($transaksi->details as $d) {
                    if ($d->ikan_id) {
                        $ikan = Ikan::find($d->ikan_id);
                        if ($ikan) {
                            $ikan->increment('stok_kg', (float) $d->jumlah_kg);
                        }
                    }
                }
            } elseif ($transaksi->ikan_id) {
                $ikan = Ikan::find($transaksi->ikan_id);
                if ($ikan) {
                    $ikan->increment('stok_kg', (float) $transaksi->jumlah_kg);
                }
            }

            $transaksi->details()->delete();
            $transaksi->delete();
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus dan stok ikan dikembalikan!');
    }
}
