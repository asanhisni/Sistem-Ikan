<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->get('bulan', date('Y-m'));
        $user   = Auth::user();

        if ($user && $user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : ($user->cabang ?? 'Cilawu');
        } else {
            $cabang = $request->get('cabang', 'semua');
        }
        
        $startBulan = Carbon::parse($bulan)->startOfMonth();
        $endBulan   = Carbon::parse($bulan)->endOfMonth();

        $query = Transaksi::with(['pelanggan', 'ikan', 'details.ikan', 'cabangRelasi', 'pembayarans'])
            ->whereBetween('tanggal_transaksi', [$startBulan, $endBulan])
            ->orderByRaw("CASE 
                WHEN status = 'belum_lunas' OR status = 'belum' THEN 1 
                WHEN status = 'dp' THEN 2 
                WHEN status = 'lunas' THEN 3 
                ELSE 4 
            END ASC")
            ->orderBy('tanggal_transaksi', 'desc');
            
        if ($cabang !== 'semua') {
            $selectedCabang = Cabang::where('nama_cabang', $cabang)->first();
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
            
        // Hitung total pendapatan (uang yang sudah masuk) & piutang (sisa tagihan)
        $totalPendapatan = $allTransaksi->sum(function ($t) {
            return (float) $t->total_dibayar;
        });

        $totalPiutang = $allTransaksi->sum(function ($t) {
            return (float) $t->sisa_tagihan;
        });

        $totalKg        = $allTransaksi->sum('jumlah_kg');
        $totalTransaksi = $allTransaksi->count();

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
            })->sum(function($item) {
                return (float) $item->total_dibayar;
            });
            $chartValues[] = (float) $val;
        }

        $daftarCabang = Cabang::pluck('nama_cabang')->toArray();
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
}
