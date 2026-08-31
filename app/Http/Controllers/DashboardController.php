<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $today  = Carbon::today();
        $bulan  = $request->get('bulan', 'ini');
        
        if ($user->role === 'kepala_cabang') {
            $cabang = $user->cabangRelasi ? $user->cabangRelasi->nama_cabang : 'Cilawu';
        } else {
            $cabang = $request->get('cabang', 'semua');
        }

        // ─── Tentukan rentang bulan ───────────────────────────────────────────
        if ($bulan === 'ini') {
            $startBulan = $today->copy()->startOfMonth();
            $endBulan   = $today->copy()->endOfMonth();
        } else {
            $startBulan = Carbon::parse($bulan)->startOfMonth();
            $endBulan   = Carbon::parse($bulan)->endOfMonth();
        }

        // ─── Base query dengan filter cabang ─────────────────────────────────
        $baseQuery = Transaksi::query();
        if ($cabang !== 'semua') {
            $selectedCabang = \App\Models\Cabang::where('nama_cabang', $cabang)->first();
            if ($selectedCabang) {
                $baseQuery->where(function($q) use ($selectedCabang, $cabang) {
                    $q->where('cabang_id', $selectedCabang->id)
                      ->orWhere('cabang', $cabang);
                });
            } else {
                $baseQuery->where('cabang', $cabang);
            }
        }

        // ─── Stat Cards ──────────────────────────────────────────────────────
        $transaksiHariIni = (clone $baseQuery)
            ->whereDate('tanggal_transaksi', $today)
            ->count();

        $belumLunas = (clone $baseQuery)
            ->where('status', 'belum_lunas')
            ->whereDate('tanggal_transaksi', $today)
            ->count();

        $totalPenjualanHariIni = (clone $baseQuery)
            ->whereDate('tanggal_transaksi', $today)
            ->sum('total');

        $penjualanBulanIni = (clone $baseQuery)
            ->whereBetween('tanggal_transaksi', [$startBulan, $endBulan])
            ->sum('total');

        // ─── Persentase perubahan vs bulan lalu ──────────────────────────────
        $prevStart = $startBulan->copy()->subMonth()->startOfMonth();
        $prevEnd   = $startBulan->copy()->subMonth()->endOfMonth();
        
        $penjualanBulanLalu = (clone $baseQuery)
            ->whereBetween('tanggal_transaksi', [$prevStart, $prevEnd])
            ->sum('total');

        $persenPerubahan = 0;
        if ($penjualanBulanLalu > 0) {
            $persenPerubahan = round((($penjualanBulanIni - $penjualanBulanLalu) / $penjualanBulanLalu) * 100, 1);
        }

        // ─── Data chart penjualan 7 hari terakhir ────────────────────────────
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i);
            $q   = Transaksi::whereDate('tanggal_transaksi', $day);
            if ($cabang !== 'semua') {
                $selectedCabang = \App\Models\Cabang::where('nama_cabang', $cabang)->first();
                if ($selectedCabang) {
                    $q->where(function($sub) use ($selectedCabang, $cabang) {
                        $sub->where('cabang_id', $selectedCabang->id)
                            ->orWhere('cabang', $cabang);
                    });
                } else {
                    $q->where('cabang', $cabang);
                }
            }
            $chartData[] = [
                'label' => $day->format('d/m'),
                'total' => $q->sum('total'),
            ];
        }

        // ─── Transaksi terbaru ────────────────────────────────────────────────
        $transaksiTerbaru = (clone $baseQuery)
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(10)
            ->get();

        // ─── Daftar cabang ────────────────────────────────────────────────────
        $daftarCabang = \App\Models\Cabang::pluck('nama_cabang')->toArray();
        if (empty($daftarCabang)) {
            $daftarCabang = ['Suci', 'Cilawu', 'Samarang'];
        }

        return view('dashboard', compact(
            'user',
            'transaksiHariIni',
            'belumLunas',
            'totalPenjualanHariIni',
            'penjualanBulanIni',
            'persenPerubahan',
            'chartData',
            'transaksiTerbaru',
            'daftarCabang',
            'bulan',
            'cabang'
        ));
    }
}