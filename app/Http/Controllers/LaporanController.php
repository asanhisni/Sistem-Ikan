<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        
        $startBulan = Carbon::parse($bulan)->startOfMonth();
        $endBulan   = Carbon::parse($bulan)->endOfMonth();

        $query = Transaksi::with(['pelanggan', 'ikan'])
            ->whereBetween('tanggal_transaksi', [$startBulan, $endBulan])
            ->orderBy('tanggal_transaksi', 'desc');
            
        if (\Illuminate\Support\Facades\Auth::user()->role === 'kepala_cabang') {
            $query->where('cabang', 'Cabang 1');
        }

        $transaksi = $query->get();
            
        $totalPendapatan = $transaksi->where('status', 'lunas')->sum('total');
        $totalPiutang = $transaksi->where('status', 'belum_lunas')->sum('total');
        $totalKg = $transaksi->sum('jumlah_kg');

        return view('laporan.index', compact('transaksi', 'bulan', 'totalPendapatan', 'totalPiutang', 'totalKg'));
    }
}
