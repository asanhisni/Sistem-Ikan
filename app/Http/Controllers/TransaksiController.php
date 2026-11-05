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
        $tanggal = $request->get('tanggal');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $cabang = $request->get('cabang');

        $user = Auth::user();
        $query = Transaksi::with(['pelanggan', 'ikan', 'cabangRelasi', 'details.ikan'])
            ->orderByRaw("CASE 
                WHEN status IN ('belum_lunas', 'belum') THEN 1 
                WHEN status = 'dp' THEN 2 
                WHEN status = 'lunas' THEN 3 
                ELSE 4 
            END ASC")
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id', 'desc');

        if ($user->role === 'kepala_cabang') {
            if ($user->cabang_id) {
                $query->where('cabang_id', $user->cabang_id);
            } elseif ($user->cabang) {
                $query->where('cabang', $user->cabang);
            }
        } elseif ($cabang && $cabang !== 'semua') {
            $selectedCabang = \App\Models\Cabang::where('nama_cabang', $cabang)->first();
            if ($selectedCabang) {
                $query->where(function($q) use ($selectedCabang, $cabang) {
                    $q->where('cabang_id', $selectedCabang->id)
                      ->orWhere('cabang', $cabang);
                });
            } else {
                $query->where('cabang', $cabang);
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

        if ($tanggal) {
            $query->whereDay('tanggal_transaksi', (int) $tanggal);
        }

        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', (int) $bulan);
        }

        if ($tahun) {
            $query->whereYear('tanggal_transaksi', (int) $tahun);
        }

        $transaksi = $query->paginate(10)->withQueryString();

        // Data opsi filter bulan & tahun
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $years = Transaksi::selectRaw('YEAR(tanggal_transaksi) as year')
            ->whereNotNull('tanggal_transaksi')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $currentYear = (int) date('Y');
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
            rsort($years);
        }

        return view('transaksi.index', compact('transaksi', 'search', 'status', 'tanggal', 'bulan', 'tahun', 'months', 'years', 'cabang'));
    }


    public function create()
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admin hanya berperan sebagai monitoring. Transaksi hanya dapat dibuat oleh Kepala Cabang.');
        }
        $ikan = Ikan::all();
        $pelanggan = Pelanggan::all();
        return view('transaksi.create', compact('ikan', 'pelanggan'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admin hanya berperan sebagai monitoring. Transaksi hanya dapat dibuat oleh Kepala Cabang.');
        }
        $request->validate([
            'pembeli_nama' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.ikan_id' => 'required|exists:ikan,id',
            'items.*.jumlah_kg' => 'required|numeric|min:0.1',
            'items.*.harga_per_kg' => 'nullable|numeric|min:0',
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
                $hargaPerKg = isset($item['harga_per_kg']) && (float)$item['harga_per_kg'] > 0
                    ? (float)$item['harga_per_kg']
                    : (float)$ikanModel->harga_per_kg;
                $subtotal = $hargaPerKg * $kg;
                $total += $subtotal;
                $totalKg += $kg;

                if (!$firstIkanId) {
                    $firstIkanId = $ikanModel->id;
                }

                $detailsData[] = [
                    'ikan_id' => $ikanModel->id,
                    'jumlah_kg' => $kg,
                    'harga_per_kg' => $hargaPerKg,
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

            if ($jumlahDibayar > 0) {
                $transaksi->pembayarans()->create([
                    'tanggal_bayar' => Carbon::now(),
                    'nominal' => $jumlahDibayar,
                    'keterangan' => $request->status === 'lunas' ? 'Pembayaran Lunas Saat Transaksi' : 'Pembayaran DP Awal',
                    'user_id' => Auth::id(),
                ]);
            }

            foreach ($detailsData as $detail) {
                $transaksi->details()->create($detail);
            }

            return redirect()->route('transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil disimpan dan stok ikan otomatis terpotong!');
        });
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['details.ikan', 'ikan', 'pelanggan', 'cabangRelasi', 'pembayarans.user']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $transaksi->load(['details.ikan', 'ikan', 'pembayarans.user']);
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
            'items.*.harga_per_kg' => 'nullable|numeric|min:0',
            'new_payment_nominal' => 'nullable|numeric|min:0',
            'new_payment_tanggal' => 'nullable|date',
            'new_payment_keterangan' => 'nullable|string|max:255',
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
                $hargaPerKg = isset($item['harga_per_kg']) && (float)$item['harga_per_kg'] > 0
                    ? (float)$item['harga_per_kg']
                    : (float)$ikanModel->harga_per_kg;
                $subtotal = $hargaPerKg * $kg;
                $total += $subtotal;
                $totalKg += $kg;

                if (!$firstIkanId) {
                    $firstIkanId = $ikanModel->id;
                }

                // Tentukan tanggal item: ambil dari input tanggal, atau created_at lama, atau tanggal_transaksi
                $itemCreatedAt = null;
                if (!empty($item['tanggal'])) {
                    try {
                        $itemCreatedAt = Carbon::parse($item['tanggal']);
                    } catch (\Exception $e) {
                        $itemCreatedAt = null;
                    }
                }

                if (!$itemCreatedAt && !empty($item['detail_id'])) {
                    $existingDetail = $transaksi->details->firstWhere('id', $item['detail_id']);
                    if ($existingDetail && $existingDetail->created_at) {
                        $itemCreatedAt = $existingDetail->created_at;
                    }
                }

                if (!$itemCreatedAt) {
                    $itemCreatedAt = $transaksi->tanggal_transaksi ?? Carbon::now();
                }

                $detailsData[] = [
                    'ikan_id' => $ikanModel->id,
                    'jumlah_kg' => $kg,
                    'harga_per_kg' => $hargaPerKg,
                    'subtotal' => $subtotal,
                    'created_at' => $itemCreatedAt,
                ];

                // Kurangi stok ikan yang baru diinput
                $ikanModel->decrement('stok_kg', $kg);
            }

            // ─── 3. SIMPAN CICILAN BARU JIKA ADA DIINPUT ───
            // BACKFILL JIKA SEBELUMNYA TIDAK ADA RECORD PEMBAYARAN TAPI ADA JUMLAH DIBAYAR
            if ($transaksi->pembayarans()->count() == 0 && (float)$transaksi->jumlah_dibayar > 0) {
                $transaksi->pembayarans()->create([
                    'tanggal_bayar' => $transaksi->tanggal_transaksi ?? Carbon::now(),
                    'nominal' => (float)$transaksi->jumlah_dibayar,
                    'keterangan' => 'Pembayaran Awal',
                    'user_id' => Auth::id(),
                ]);
            }

            if ($request->filled('new_payment_nominal') && (float)$request->new_payment_nominal > 0) {
                $transaksi->pembayarans()->create([
                    'tanggal_bayar' => $request->new_payment_tanggal ? Carbon::parse($request->new_payment_tanggal) : Carbon::now(),
                    'nominal' => (float)$request->new_payment_nominal,
                    'keterangan' => $request->new_payment_keterangan ?: null,
                    'user_id' => Auth::id(),
                ]);
            }

            // Hitung total dari seluruh riwayat pembayaran
            $totalRiwayat = (float) $transaksi->pembayarans()->sum('nominal');

            // Jika belum ada riwayat sama sekali tapi status dipilih lunas / dp
            if ($totalRiwayat == 0 && $request->status === 'lunas') {
                $transaksi->pembayarans()->create([
                    'tanggal_bayar' => Carbon::now(),
                    'nominal' => $total,
                    'keterangan' => 'Pembayaran Lunas Saat Ubah Data',
                    'user_id' => Auth::id(),
                ]);
                $totalRiwayat = $total;
            } elseif ($totalRiwayat == 0 && $request->status === 'dp' && (float)$request->jumlah_dibayar > 0) {
                $dpAwal = (float)$request->jumlah_dibayar;
                $transaksi->pembayarans()->create([
                    'tanggal_bayar' => Carbon::now(),
                    'nominal' => $dpAwal,
                    'keterangan' => 'Pembayaran DP Saat Ubah Data',
                    'user_id' => Auth::id(),
                ]);
                $totalRiwayat = $dpAwal;
            }

            $sisa = (float) $total - $totalRiwayat;
            if ($sisa <= 0 && $totalRiwayat > 0) {
                $statusFix = 'lunas';
                $jumlahDibayar = $totalRiwayat;
            } elseif ($totalRiwayat > 0) {
                $statusFix = 'dp';
                $jumlahDibayar = $totalRiwayat;
            } else {
                $statusFix = 'belum_lunas';
                $jumlahDibayar = 0;
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
                'status' => $statusFix,
                'jumlah_dibayar' => $jumlahDibayar,
                'keterangan' => $request->keterangan,
            ]);

            // Hapus detail lama dan masukkan yang baru dengan mempertahankan tanggal aslinya
            $transaksi->details()->delete();
            foreach ($detailsData as $detail) {
                $newDetail = new \App\Models\TransaksiDetail([
                    'transaksi_id' => $transaksi->id,
                    'ikan_id' => $detail['ikan_id'],
                    'jumlah_kg' => $detail['jumlah_kg'],
                    'harga_per_kg' => $detail['harga_per_kg'],
                    'subtotal' => $detail['subtotal'],
                    'created_at' => $detail['created_at'],
                ]);
                $newDetail->created_at = $detail['created_at'];
                $newDetail->save();
            }

            return redirect()->route('transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil diubah, pembayaran & stok ikan diperbarui!');
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
