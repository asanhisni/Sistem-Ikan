@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .dashboard-container {
        max-width: 980px;
        margin: 0 auto;
        padding-bottom: 24px;
    }

    /* ─── KARTU SAMBUTAN (RINGKAS & BERSIH) ─── */
    .welcome-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        padding: 14px 18px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .welcome-text-wrap {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .welcome-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .welcome-sub {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 500;
    }

    .welcome-pills {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 11px;
        font-weight: 600;
        color: #475569;
    }

    .meta-pill i {
        color: #2563eb;
        font-size: 10px;
    }

    @media (max-width: 640px) {
        .welcome-card {
            padding: 12px 14px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }

    /* ─── GRID 2x2 STATISTIK (PADAT & SIMETRIS) ─── */
    .stat-grid-2x2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: 12px 14px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 82px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 3.5px;
        border-radius: 4px 0 0 4px;
    }

    .stat-card.blue::before  { background: #2563eb; }
    .stat-card.red::before   { background: #e11d48; }
    .stat-card.green::before { background: #059669; }
    .stat-card.teal::before  { background: #0d9488; }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        line-height: 1.2;
    }

    .stat-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .stat-icon.blue  { background: #eff6ff; color: #2563eb; }
    .stat-icon.red   { background: #fff1f2; color: #e11d48; }
    .stat-icon.green { background: #ecfdf5; color: #059669; }
    .stat-icon.teal  { background: #f0fdfa; color: #0d9488; }

    .stat-value {
        font-size: clamp(16px, 1.5vw, 22px);
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.15;
    }

    .stat-value.blue  { color: #2563eb; }
    .stat-value.red   { color: #e11d48; }
    .stat-value.green { color: #059669; }
    .stat-value.teal  { color: #0d9488; }

    @media (max-width: 480px) {
        .stat-card {
            padding: 10px 12px;
            min-height: 76px;
        }
        .stat-label {
            font-size: 10px;
        }
        .stat-value {
            font-size: 15px;
        }
        .stat-icon {
            width: 24px;
            height: 24px;
            font-size: 11px;
        }
    }

    /* ─── 3 TOMBOL PINTASAN (SEIMBANG & RAPI) ─── */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .quick-btn {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #0f172a;
        font-weight: 700;
        font-size: 13px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
        transition: all 0.15s ease;
        box-sizing: border-box;
    }

    .quick-btn:hover {
        border-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }

    .quick-btn:active {
        transform: scale(0.98);
    }

    .quick-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .quick-btn-icon.blue   { background: #eff6ff; color: #2563eb; }
    .quick-btn-icon.sky    { background: #e0f2fe; color: #0284c7; }
    .quick-btn-icon.green  { background: #ecfdf5; color: #059669; }

    @media (max-width: 640px) {
        .quick-actions-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .quick-btn {
            padding: 11px 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">

    <!-- 1. KARTU SAMBUTAN (RINGKAS & RAPI) -->
    <div class="welcome-card">
        <div class="welcome-text-wrap">
            <div class="welcome-title">Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</div>
            <div class="welcome-sub">Ringkasan aktivitas transaksi hari ini.</div>
        </div>
        <div class="welcome-pills">
            <span class="meta-pill">
                <i class="fas fa-user-shield"></i>
                <span>{{ Auth::user()->role === 'admin' ? 'Admin' : 'Kepala Cabang' }}</span>
            </span>
            <span class="meta-pill">
                <i class="fas fa-store"></i>
                <span>{{ Auth::user()->role === 'admin' ? 'Pusat (Semua Cabang)' : (Auth::user()->cabangRelasi ? Auth::user()->cabangRelasi->nama_cabang : (Auth::user()->cabang ?? 'Cabang')) }}</span>
            </span>
            <span class="meta-pill">
                <i class="fas fa-calendar-day"></i>
                <span>{{ now()->isoFormat('D MMM YYYY') }}</span>
            </span>
        </div>
    </div>

    <!-- 2. EMPAT KARTU STATISTIK (GRID 2x2 PADAT & SIMETRIS) -->
    <div class="stat-grid-2x2">
        <!-- Card 1: Transaksi Hari Ini -->
        <div class="stat-card blue">
            <div class="stat-header">
                <span class="stat-label">Transaksi Hari Ini</span>
                <div class="stat-icon blue">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-value blue">{{ $transaksiHariIni }}</div>
        </div>

        <!-- Card 2: Belum Lunas -->
        <div class="stat-card red">
            <div class="stat-header">
                <span class="stat-label">Belum Lunas</span>
                <div class="stat-icon red">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
            <div class="stat-value red">{{ $belumLunas }}</div>
        </div>

        <!-- Card 3: Omset Hari Ini -->
        <div class="stat-card green">
            <div class="stat-header">
                <span class="stat-label">Omset Hari Ini</span>
                <div class="stat-icon green">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="stat-value green">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</div>
        </div>

        <!-- Card 4: Omset Bulan Ini -->
        <div class="stat-card teal">
            <div class="stat-header">
                <span class="stat-label">Omset Bulan Ini</span>
                <div class="stat-icon teal">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-value teal">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- 3. TIGA TOMBOL PINTASAN (SEIMBANG & RAPI) -->
    <div class="quick-actions-grid">
        <a href="{{ route('transaksi.create') }}" class="quick-btn">
            <div class="quick-btn-icon blue">
                <i class="fas fa-plus"></i>
            </div>
            <span>Buat Transaksi Baru</span>
        </a>
        <a href="{{ route('transaksi.index') }}" class="quick-btn">
            <div class="quick-btn-icon sky">
                <i class="fas fa-receipt"></i>
            </div>
            <span>Daftar Transaksi</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="quick-btn">
            <div class="quick-btn-icon green">
                <i class="fas fa-chart-bar"></i>
            </div>
            <span>Lihat Laporan & Grafik</span>
        </a>
    </div>

</div>
@endsection
