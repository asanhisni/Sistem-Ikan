@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@push('styles')
<style>
    .page-shell {
        max-width: 1140px;
        margin: 0 auto;
        padding-bottom: 30px;
    }

    /* ─── TAB NAVIGASI LAPORAN (PENJUALAN VS STOK) ─── */
    .laporan-nav-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        background: #eef2f6;
        padding: 5px;
        border-radius: 14px;
        width: fit-content;
    }

    .laporan-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .laporan-tab-btn.active {
        background: #ffffff;
        color: #2563eb;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }

    .laporan-tab-btn:hover:not(.active) {
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .laporan-nav-tabs {
            width: 100%;
        }
        .laporan-tab-btn {
            flex: 1;
            justify-content: center;
            padding: 8px 10px;
            font-size: 12px;
        }
    }

    /* ─── FILTER PANEL (SAMA PERSIS DENGAN LAPORAN STOK IKAN) ─── */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 14px 16px;
        border: 1px solid rgba(226, 232, 240, 0.85);
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
        margin-bottom: 16px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-item label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .filter-item label i {
        color: #2563eb;
        font-size: 12px;
    }

    .filter-select {
        width: 100%;
        height: 40px;
        padding: 6px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 13px;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        transition: all 0.2s ease;
        appearance: auto;
    }

    .filter-select:focus {
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .filter-static-branch {
        width: 100%;
        height: 40px;
        padding: 6px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        box-sizing: border-box;
    }

    .filter-static-branch i.text-blue {
        color: #2563eb;
    }

    @media (max-width: 560px) {
        .filter-form {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    /* ─── SUMMARY CARDS (4 SEJAJAR & SIMETRIS) ─── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .summary-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        padding: 14px 16px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 86px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }

    .summary-card.pendapatan::before { background: #059669; }
    .summary-card.piutang::before    { background: #e11d48; }
    .summary-card.terjual::before    { background: #2563eb; }
    .summary-card.transaksi::before  { background: #7c3aed; }

    .summary-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .summary-value {
        font-size: clamp(16px, 1.4vw, 20px);
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .summary-card.pendapatan .summary-value { color: #059669; }
    .summary-card.piutang .summary-value    { color: #e11d48; }
    .summary-card.terjual .summary-value    { color: #2563eb; }
    .summary-card.transaksi .summary-value  { color: #7c3aed; }

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        .summary-card {
            padding: 11px 12px;
            min-height: 78px;
        }
        .summary-title {
            font-size: 10px;
        }
        .summary-value {
            font-size: 15px;
        }
    }

    /* ─── GRAFIK PENDAPATAN HARIAN ─── */
    .revenue-panel {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        padding: 14px 18px;
        margin-bottom: 18px;
    }

    .revenue-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .revenue-panel-title {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .revenue-panel-title i {
        color: #2563eb;
    }

    .revenue-panel-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }

    .revenue-badge-total {
        font-weight: 800;
        font-size: 12px;
        color: #047857;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        padding: 4px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .revenue-chart-wrap {
        position: relative;
        height: 165px;
        width: 100%;
    }

    /* ─── RINCIAN TRANSAKSI ─── */
    .section-header-wrap {
        display: flex;
        align-items: center;
        margin: 24px 0 14px 0;
        padding: 0 4px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 9px;
        letter-spacing: -0.01em;
    }

    .section-title-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 13px;
        flex-shrink: 0;
    }

    .table-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .tabel-sederhana {
        width: 100%;
        min-width: 740px;
        border-collapse: collapse;
    }

    .tabel-sederhana th {
        text-align: left;
        padding: 10px 14px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid #eef2f6;
    }

    .tabel-sederhana td {
        padding: 11px 14px;
        border-bottom: 1px solid #f8fafc;
        color: #0f172a;
        font-size: 13px;
        vertical-align: middle;
    }

    .tabel-sederhana tbody tr:hover {
        background: #f8fbff;
    }

    .tabel-sederhana tbody tr:last-child td {
        border-bottom: none;
    }

    .badge-cabang-sm {
        font-size: 11px;
        font-weight: 700;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-block;
    }

    /* ─── STATUS BADGES ─── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-badge.lunas {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .status-badge.dp {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-badge.belum {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* ─── MOBILE CARD LIST ─── */
    .mobile-list {
        display: none;
    }

    .mobile-tx-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: 14px;
        margin-bottom: 10px;
    }

    .mobile-tx-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 6px;
    }

    .mobile-tx-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        text-transform: capitalize;
    }

    .mobile-tx-total {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
    }

    .mobile-tx-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .mobile-tx-items {
        padding: 8px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 8px;
        font-size: 12.5px;
        color: #1e293b;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .mobile-tx-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-tx-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 640px) {
        .table-card {
            display: none;
        }
        .mobile-list {
            display: block;
        }
    }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 140px;
        text-align: center;
        color: #64748b;
        padding: 24px 16px;
    }

    .empty-state i {
        font-size: 32px;
        color: #cbd5e1;
    }

    /* ─── FLUENT MINIMALIST PAGINATION ─── */
    .fluent-pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 18px;
        margin-bottom: 6px;
    }

    .fluent-pagination {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        padding: 5px 8px;
        border-radius: 14px;
        border: 1.5px solid #eef2f6;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
    }

    .fluent-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 10px;
        border: 1.5px solid transparent;
        background: transparent;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.18s ease;
        user-select: none;
        box-sizing: border-box;
    }

    .fluent-page-btn:hover:not(.disabled):not(.active) {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #e2e8f0;
    }

    .fluent-page-btn.active {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
    }

    .fluent-page-btn.disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
        color: #94a3b8;
    }

    .fluent-page-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 36px;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 700;
    }

    .fluent-page-info {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        text-align: center;
    }

    /* ─── AJAX LOADING STATE ─── */
    #laporan-main-content,
    #laporan-rincian-container {
        position: relative;
        transition: opacity 0.2s ease, filter 0.2s ease;
    }

    #laporan-main-content.is-loading,
    #laporan-rincian-container.is-loading {
        opacity: 0.45;
        filter: blur(1px);
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="page-shell">

    <!-- TAB SWITCHER LAPORAN -->
    <div class="laporan-nav-tabs">
        <a href="{{ route('laporan.index') }}" class="laporan-tab-btn active">
            <i class="fas fa-chart-line"></i>
            <span>Laporan Penjualan</span>
        </a>
        <a href="{{ route('laporan.stok') }}" class="laporan-tab-btn">
            <i class="fas fa-boxes-stacked"></i>
            <span>Laporan Stok Ikan</span>
        </a>
    </div>

    <!-- 1. PANEL FILTER (BULAN & CABANG) -->
    <div class="filter-card">
        <form method="GET" action="{{ route('laporan.index') }}" class="filter-form" id="laporanFilterForm">
            <!-- Filter Bulan -->
            <div class="filter-item">
                <label for="filterBulan">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Pilih Bulan</span>
                </label>
                <select name="bulan" id="filterBulan" class="filter-select">
                    @for($m = 0; $m < 12; $m++)
                        @php $mo = now()->subMonths($m)->format('Y-m'); @endphp
                        <option value="{{ $mo }}" {{ $bulan === $mo ? 'selected' : '' }}>
                            {{ now()->subMonths($m)->isoFormat('MMMM YYYY') }}
                        </option>
                    @endfor
                </select>
            </div>

            @if(Auth::user()->role === 'admin')
            <!-- Filter Cabang (Khusus Admin) -->
            <div class="filter-item">
                <label for="filterCabang">
                    <i class="fas fa-store"></i>
                    <span>Pilih Cabang</span>
                </label>
                <select name="cabang" id="filterCabang" class="filter-select">
                    <option value="semua" {{ ($cabang ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Cabang</option>
                    <option value="Suci" {{ ($cabang ?? '') === 'Suci' ? 'selected' : '' }}>Suci</option>
                    <option value="Cilawu" {{ ($cabang ?? '') === 'Cilawu' ? 'selected' : '' }}>Cilawu</option>
                    <option value="Samarang" {{ ($cabang ?? '') === 'Samarang' ? 'selected' : '' }}>Samarang</option>
                </select>
            </div>
            @else
            <!-- Lokasi Cabang Tetap (Khusus Kepala Cabang) -->
            <div class="filter-item">
                <label>
                    <i class="fas fa-location-dot"></i>
                    <span>Lokasi Cabang</span>
                </label>
                <div class="filter-static-branch">
                    <i class="fas fa-store text-blue"></i>
                    <span>Cabang {{ Auth::user()->cabangRelasi ? Auth::user()->cabangRelasi->nama_cabang : (Auth::user()->cabang ?? $cabang) }}</span>
                </div>
                <input type="hidden" name="cabang" value="{{ Auth::user()->cabangRelasi ? Auth::user()->cabangRelasi->nama_cabang : (Auth::user()->cabang ?? $cabang) }}">
            </div>
            @endif
        </form>
    </div>

    <!-- MAIN DATA CONTAINER UNTUK AJAX FILTER -->
    <div id="laporan-main-content">
        <!-- 2. EMPAT KARTU RINGKASAN (SEJAJAR & SIMETRIS) -->
        <div class="summary-grid">
            <!-- Pendapatan (Lunas) -->
            <div class="summary-card pendapatan">
                <div class="summary-title">
                    <span>Pendapatan Lunas</span>
                    <i class="fas fa-check-circle" style="color: #059669; font-size: 13px;"></i>
                </div>
                <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>

            <!-- Piutang (Belum Lunas) -->
            <div class="summary-card piutang">
                <div class="summary-title">
                    <span>Piutang</span>
                    <i class="fas fa-clock" style="color: #e11d48; font-size: 13px;"></i>
                </div>
                <div class="summary-value">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
            </div>

            <!-- Total Ikan Terjual -->
            <div class="summary-card terjual">
                <div class="summary-title">
                    <span>Total Ikan Terjual</span>
                    <i class="fas fa-fish" style="color: #2563eb; font-size: 13px;"></i>
                </div>
                <div class="summary-value">{{ number_format($totalKg, 1, ',', '.') }} Kg</div>
            </div>

            <!-- Jumlah Transaksi -->
            <div class="summary-card transaksi">
                <div class="summary-title">
                    <span>Jumlah Transaksi</span>
                    <i class="fas fa-receipt" style="color: #7c3aed; font-size: 13px;"></i>
                </div>
                <div class="summary-value">{{ $totalTransaksi }} Transaksi</div>
            </div>
        </div>

        <!-- 3. GRAFIK PENDAPATAN HARIAN (RINGKAS & PROPORIONAL) -->
        <div class="revenue-panel">
            <div class="revenue-panel-header">
                <div>
                    <div class="revenue-panel-title">
                        <i class="fas fa-chart-bar"></i>
                        <span>Grafik Pendapatan Harian</span>
                    </div>
                    <div class="revenue-panel-sub">
                        Periode {{ Carbon\Carbon::parse($bulan)->isoFormat('MMMM YYYY') }} &bull; Cabang: <strong>{{ $cabang === 'semua' ? 'Semua Cabang' : $cabang }}</strong>
                    </div>
                </div>
                <div class="revenue-badge-total">
                    <i class="fas fa-wallet"></i>
                    <span>Total: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="revenue-chart-wrap" id="revenueChartContainer" data-labels="{{ json_encode($chartLabels ?? []) }}" data-values="{{ json_encode($chartValues ?? []) }}">
                <canvas id="laporanRevenueChart"></canvas>
            </div>
        </div>

        <!-- 4. RINCIAN TRANSAKSI (CONTAINER AJAX PAGINATION) -->
        <div id="laporan-rincian-container">
            <div class="section-header-wrap">
                <div class="section-title">
                    <span class="section-title-icon">
                        <i class="fas fa-list-alt"></i>
                    </span>
                    <span>Rincian Transaksi ({{ $cabang === 'semua' ? 'Semua Cabang' : $cabang }})</span>
                </div>
            </div>

            <!-- Tampilan Desktop: Tabel Rapi -->
            <div class="table-card">
                <div class="table-wrapper">
                    <table class="tabel-sederhana">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pembeli</th>
                                <th>Cabang</th>
                                <th>Ikan</th>
                                <th>Berat</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $t)
                            <tr>
                                <td style="color: #64748b; font-size: 12px; white-space: nowrap;">
                                    {{ $t->tanggal_transaksi->format('d/m/Y') }}
                                </td>
                                <td style="font-weight: 700; color: #0f172a;">
                                    {{ $t->pembeli_nama }}
                                </td>
                                <td>
                                    <span class="badge-cabang-sm">
                                        {{ $t->cabangRelasi ? $t->cabangRelasi->nama_cabang : ($t->cabang ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    @if($t->details && $t->details->count() > 0)
                                        <div style="display: flex; flex-direction: column; gap: 3px;">
                                            @foreach($t->details as $d)
                                                <div>{{ $d->ikan ? $d->ikan->nama_ikan : '-' }} <span style="color: #64748b; font-size: 12px;">({{ $d->jumlah_kg }} Kg)</span></div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ $t->ikan ? $t->ikan->nama_ikan : '-' }}
                                    @endif
                                </td>
                                <td style="font-weight: 600; color: #475569;">
                                    {{ number_format($t->jumlah_kg, 2) }} Kg
                                </td>
                                <td style="font-weight: 800; color: #0f172a; white-space: nowrap;">
                                    Rp {{ number_format($t->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($t->status == 'lunas')
                                        <span class="status-badge lunas"><i class="fas fa-check"></i> LUNAS</span>
                                    @elseif($t->status == 'dp')
                                        <span class="status-badge dp"><i class="fas fa-clock"></i> DP</span>
                                    @else
                                        <span class="status-badge belum"><i class="fas fa-times"></i> BELUM</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <div>Tidak ada transaksi pada filter ini.</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tampilan Mobile: Card Rapi & Bersih -->
            <div class="mobile-list">
                @forelse($transaksi as $t)
                <div class="mobile-tx-card">
                    <div class="mobile-tx-head">
                        <div class="mobile-tx-name">{{ $t->pembeli_nama }}</div>
                        <div class="mobile-tx-total">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="mobile-tx-meta">
                        <span>{{ $t->tanggal_transaksi->format('d M Y') }} &bull; {{ $t->cabangRelasi ? $t->cabangRelasi->nama_cabang : ($t->cabang ?? '-') }}</span>
                        <span style="font-weight: 700; color: #0f172a;">{{ number_format($t->jumlah_kg, 2) }} Kg</span>
                    </div>
                    
                    <div class="mobile-tx-items">
                        @if($t->details && $t->details->count() > 0)
                            @foreach($t->details as $d)
                                <div class="mobile-tx-item-row">
                                    <span>• {{ $d->ikan ? $d->ikan->nama_ikan : '-' }}</span>
                                    <span style="color: #64748b; font-family: monospace;">{{ number_format($d->jumlah_kg, 2) }} Kg</span>
                                </div>
                            @endforeach
                        @else
                            <div class="mobile-tx-item-row">
                                <span>• {{ $t->ikan ? $t->ikan->nama_ikan : '-' }}</span>
                                <span style="color: #64748b; font-family: monospace;">{{ number_format($t->jumlah_kg, 2) }} Kg</span>
                            </div>
                        @endif
                    </div>

                    <div class="mobile-tx-footer">
                        @if($t->status == 'lunas')
                            <span class="status-badge lunas"><i class="fas fa-check"></i> LUNAS</span>
                        @elseif($t->status == 'dp')
                            <span class="status-badge dp"><i class="fas fa-clock"></i> DP</span>
                        @else
                            <span class="status-badge belum"><i class="fas fa-times"></i> BELUM</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <div>Tidak ada transaksi pada filter ini.</div>
                </div>
                @endforelse
            </div>

            <!-- 5. TOMBOL PAGINATION FLUENT MINIMALIST (POSISI TENGAH) -->
            @if ($transaksi->hasPages())
            <div class="fluent-pagination-container">
                <nav class="fluent-pagination" role="navigation" aria-label="Navigasi Halaman">
                    {{-- Tombol Sebelumnya --}}
                    @if ($transaksi->onFirstPage())
                        <span class="fluent-page-btn disabled" aria-disabled="true" title="Halaman Sebelumnya">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $transaksi->previousPageUrl() }}" class="fluent-page-btn" rel="prev" title="Halaman Sebelumnya">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @php
                        $start = max(1, $transaksi->currentPage() - 2);
                        $end = min($transaksi->lastPage(), $transaksi->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $transaksi->url(1) }}" class="fluent-page-btn">1</a>
                        @if($start > 2)
                            <span class="fluent-page-dots">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $transaksi->currentPage())
                            <span class="fluent-page-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $transaksi->url($page) }}" class="fluent-page-btn">{{ $page }}</a>
                        @endif
                    @endfor

                    @if($end < $transaksi->lastPage())
                        @if($end < $transaksi->lastPage() - 1)
                            <span class="fluent-page-dots">...</span>
                        @endif
                        <a href="{{ $transaksi->url($transaksi->lastPage()) }}" class="fluent-page-btn">{{ $transaksi->lastPage() }}</a>
                    @endif

                    {{-- Tombol Selanjutnya --}}
                    @if ($transaksi->hasMorePages())
                        <a href="{{ $transaksi->nextPageUrl() }}" class="fluent-page-btn" rel="next" title="Halaman Selanjutnya">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="fluent-page-btn disabled" aria-disabled="true" title="Halaman Selanjutnya">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </nav>
                <div class="fluent-page-info">
                    Menampilkan {{ $transaksi->firstItem() ?? 0 }} - {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} transaksi
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let laporanChart = null;

    function renderLaporanChart(labels, values) {
        const canvas = document.getElementById('laporanRevenueChart');
        if (!canvas) return;

        if (laporanChart) {
            laporanChart.destroy();
            laporanChart = null;
        }

        if (!labels || labels.length === 0) return;

        const ctx = canvas.getContext('2d');
        laporanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: values,
                    borderRadius: 6,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    hoverBackgroundColor: '#1d4ed8',
                    borderWidth: 0,
                    maxBarThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: '700' },
                        bodyFont: { size: 12, weight: '600' },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => 'Pendapatan: Rp ' + Number(ctx.raw).toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#64748b',
                            font: { size: 10.5, weight: '600' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: value => {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                return 'Rp ' + value;
                            },
                            color: '#64748b',
                            font: { size: 10.5, weight: '600' }
                        }
                    }
                }
            }
        });
    }

    // Inisialisasi awal chart dari data-attribute
    document.addEventListener('DOMContentLoaded', function() {
        const chartWrap = document.getElementById('revenueChartContainer');
        if (chartWrap) {
            try {
                const labels = JSON.parse(chartWrap.getAttribute('data-labels') || '[]');
                const values = JSON.parse(chartWrap.getAttribute('data-values') || '[]');
                renderLaporanChart(labels, values);
            } catch (e) {
                console.error('Failed to parse initial chart data:', e);
            }
        }
    });

    // ─── AJAX PAGINATION RINCIAN TRANSAKSI (TANPA REFRESH HALAMAN) ───
    async function loadLaporanRincian(url, updateHistory = true) {
        const rincianContainer = document.getElementById('laporan-rincian-container');
        if (!rincianContainer) return;

        rincianContainer.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Gagal mengambil rincian data transaksi');

            const htmlText = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            const newContent = doc.getElementById('laporan-rincian-container');

            if (newContent) {
                rincianContainer.innerHTML = newContent.innerHTML;
            }

            if (updateHistory) {
                history.pushState(null, '', url);
            }

            // Scroll halus ke awal bagian rincian transaksi
            rincianContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            console.error('AJAX Error:', error);
            window.location.href = url;
        } finally {
            rincianContainer.classList.remove('is-loading');
        }
    }

    // ─── AJAX FILTER PENJUALAN (UPDATE RINGKASAN, GRAFIK & RINCIAN) ───
    async function loadLaporanFull(url, updateHistory = true) {
        const mainContainer = document.getElementById('laporan-main-content');
        if (!mainContainer) return;

        mainContainer.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Gagal memuat laporan');

            const htmlText = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            const newMain = doc.getElementById('laporan-main-content');

            if (newMain) {
                mainContainer.innerHTML = newMain.innerHTML;

                // Re-render chart dengan data baru
                const chartWrap = mainContainer.querySelector('#revenueChartContainer');
                if (chartWrap) {
                    try {
                        const labels = JSON.parse(chartWrap.getAttribute('data-labels') || '[]');
                        const values = JSON.parse(chartWrap.getAttribute('data-values') || '[]');
                        renderLaporanChart(labels, values);
                    } catch (e) {
                        console.error('Failed to parse dynamic chart data:', e);
                    }
                }
            }

            if (updateHistory) {
                history.pushState(null, '', url);
            }
        } catch (error) {
            console.error('AJAX Filter Error:', error);
            window.location.href = url;
        } finally {
            mainContainer.classList.remove('is-loading');
        }
    }

    // Tangkap klik pagination rincian transaksi
    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('#laporan-rincian-container .fluent-page-btn[href]');
        if (pageLink) {
            e.preventDefault();
            loadLaporanRincian(pageLink.href);
        }
    });

    // ─── TRIGGER FILTER OTOMATIS (INSTAN SAAT DROPDOWN BULAN / CABANG BERUBAH) ───
    function executeLaporanFilter() {
        const filterForm = document.getElementById('laporanFilterForm');
        if (!filterForm) return;
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const actionUrl = filterForm.action + '?' + params.toString();
        loadLaporanFull(actionUrl);
    }

    const filterBulan = document.getElementById('filterBulan');
    if (filterBulan) {
        filterBulan.addEventListener('change', executeLaporanFilter);
    }

    const filterCabang = document.getElementById('filterCabang');
    if (filterCabang) {
        filterCabang.addEventListener('change', executeLaporanFilter);
    }

    // Support browser Back/Forward navigation
    window.addEventListener('popstate', function() {
        loadLaporanFull(window.location.href, false);
    });
</script>
@endpush
