<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->get('bulan', date('Y-m'));
        $user   = \Illuminate\Support\Facades\Auth::user();

        if ($user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : ($user->cabang ?? 'Cilawu');
        } else {
            $cabang = $request->get('cabang', 'semua');
        }
        
        $startBulan = Carbon::parse($bulan)->startOfMonth();
        $endBulan   = Carbon::parse($bulan)->endOfMonth();

        $query = Transaksi::with(['pelanggan', 'ikan', 'details.ikan', 'cabangRelasi'])
            ->whereBetween('tanggal_transaksi', [$startBulan, $endBulan])
            ->orderBy('tanggal_transaksi', 'desc');
            
        if ($cabang !== 'semua') {
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

        $allTransaksi = (clone $query)->get();
            
        $totalPendapatan = $allTransaksi->where('status', 'lunas')->sum('total');
        $totalPiutang    = $allTransaksi->where('status', 'belum_lunas')->sum('total');
        $totalKg         = $allTransaksi->sum('jumlah_kg');
        $totalTransaksi  = $allTransaksi->count();

        // Data transaksi per halaman (10 per halaman)
        $transaksi = $query->paginate(10)->withQueryString();

        // Data chart pendapatan harian dalam bulan laporan
        $chartLabels = [];
        $chartValues = [];
        $daysInMonth = $startBulan->daysInMonth;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $tgl = $startBulan->copy()->day($d);
            if ($tgl->isFuture() && $startBulan->isCurrentMonth()) {
                break;
            }
            $tglStr = $tgl->format('Y-m-d');
            $chartLabels[] = $tgl->format('d/m');
            $val = $allTransaksi->filter(function($item) use ($tglStr) {
                return $item->tanggal_transaksi->format('Y-m-d') === $tglStr;
            })->sum('total');
            $chartValues[] = (float) $val;
        }

        $daftarCabang = \App\Models\Cabang::pluck('nama_cabang')->toArray();
        if (empty($daftarCabang)) {
            $daftarCabang = ['Suci', 'Cilawu', 'Samarang'];
        }

        return view('laporan.index', compact(
            'transaksi',
            'bulan',
            'cabang',
            'totalPendapatan',
            'totalPiutang',
            'totalKg',
            'totalTransaksi',
            'chartLabels',
            'chartValues',
            'daftarCabang'
        ));
    }

    public function stok(Request $request)
    {
        $bulan  = $request->get('bulan', date('Y-m'));
        $user   = \Illuminate\Support\Facades\Auth::user();

        if ($user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : ($user->cabang ?? 'Cilawu');
        } else {
            $cabang = $request->get('cabang', 'semua');
        }

        $selectedCabang = null;
        if ($cabang !== 'semua') {
            $selectedCabang = \App\Models\Cabang::where('nama_cabang', $cabang)->first();
        }

        $ikanList = \App\Models\Ikan::orderBy('nama_ikan', 'asc')->get();

        if ($selectedCabang) {
            // Ambil stok spesifik cabang dari tabel stok_cabang
            $stokMap = \App\Models\StokCabang::where('cabang_id', $selectedCabang->id)
                ->pluck('stok_kg', 'ikan_id')
                ->toArray();

            foreach ($ikanList as $ikan) {
                $ikan->stok_kg = isset($stokMap[$ikan->id]) ? (float) $stokMap[$ikan->id] : 0.0;
            }
        }

        $totalStokKg  = (float) $ikanList->sum('stok_kg');
        $totalJenis   = $ikanList->count();
        $totalNilaiRp = (float) $ikanList->sum(function($item) {
            return $item->stok_kg * $item->harga_per_kg;
        });

        // Hitung penjualan per ikan dalam bulan & cabang yang dipilih
        $startBulan = Carbon::parse($bulan)->startOfMonth();
        $endBulan   = Carbon::parse($bulan)->endOfMonth();

        $detailQuery = \App\Models\TransaksiDetail::whereHas('transaksi', function($q) use ($startBulan, $endBulan, $cabang, $selectedCabang) {
            $q->whereBetween('tanggal_transaksi', [$startBulan, $endBulan]);
            if ($cabang !== 'semua') {
                if ($selectedCabang) {
                    $q->where(function($sub) use ($selectedCabang, $cabang) {
                        $sub->where('cabang_id', $selectedCabang->id)
                            ->orWhere('cabang', $cabang);
                    });
                } else {
                    $q->where('cabang', $cabang);
                }
            }
        });

        $penjualanIkan = $detailQuery->selectRaw('ikan_id, SUM(jumlah_kg) as total_terjual_kg, SUM(subtotal) as total_penjualan')
          ->groupBy('ikan_id')
          ->get()
          ->keyBy('ikan_id');

        $totalTerjualKg   = (float) $penjualanIkan->sum('total_terjual_kg');
        $totalPenjualanRp = (float) $penjualanIkan->sum('total_penjualan');

        $daftarCabang = \App\Models\Cabang::pluck('nama_cabang')->toArray();
        if (empty($daftarCabang)) {
            $daftarCabang = ['Cilawu', 'Suci', 'Samarang'];
        }

        return view('laporan.stok', compact(
            'ikanList',
            'totalStokKg',
            'totalJenis',
            'totalNilaiRp',
            'totalTerjualKg',
            'totalPenjualanRp',
            'bulan',
            'cabang',
            'penjualanIkan',
            'daftarCabang'
        ));
    }
}
