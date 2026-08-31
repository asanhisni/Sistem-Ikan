@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .content-inner {
        max-width: 1180px;
        margin: 0 auto;
        padding-bottom: 12px;
    }

    /* ─── WELCOME BANNER SIMPLE ─── */
    .welcome-strip {
        margin-bottom: 16px;
        padding: 16px 18px;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(240,247,255,0.95));
        border: 1px solid rgba(30,136,229,0.08);
        box-shadow: 0 8px 24px rgba(15, 23, 36, 0.04);
    }

    .welcome-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .welcome-title {
        font-size: clamp(18px, 2vw, 24px);
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
        line-height: 1.2;
    }

    .welcome-sub {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Tombol Toggle Detail Kecil di Mobile */
    .btn-toggle-meta {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-toggle-meta:hover {
        background: #e2e8f0;
    }

    .welcome-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed rgba(148,163,184,0.25);
    }

    .welcome-meta .meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,0.8);
        border: 1px solid rgba(148,163,184,0.2);
        font-size: 11px;
        font-weight: 600;
    }

    /* ─── STAT CARDS GRID 2x2 PADA MOBILE ─── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
        align-items: stretch;
    }

    .stat-card {
        position: relative;
        padding: 16px;
        border-radius: 16px;
        background: linear-gradient(180deg, rgba(255,255,255,0.985), rgba(250,252,255,0.96));
        border: 1px solid rgba(15, 23, 36, 0.05);
        box-shadow: 0 6px 20px rgba(11,35,60,0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        border-radius: 12px 0 0 12px;
    }

    .stat-card.blue::before  { background: var(--blue); }
    .stat-card.red::before   { background: var(--red); }
    .stat-card.green::before { background: var(--green); }
    .stat-card.teal::before  { background: #00897b; }

    .stat-icon-wrap {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 12px;
        font-size: 18px;
    }

    .stat-icon-wrap.blue  { background: var(--blue-light); color: var(--blue); }
    .stat-icon-wrap.red   { background: var(--red-light); color: var(--red); }
    .stat-icon-wrap.green { background: var(--green-light); color: var(--green); }
    .stat-icon-wrap.teal  { background: rgba(0,137,123,0.08); color: #00897b; }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .stat-value {
        font-size: clamp(20px, 1.8vw, 28px);
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--text);
        line-height: 1.1;
    }

    .stat-value.blue  { color: var(--blue); }
    .stat-value.red   { color: var(--red); }
    .stat-value.green { color: var(--green); }
    .stat-value.teal  { color: #00897b; }

    /* Quick Shortcut Box */
    .quick-actions-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .quick-btn {
        flex: 1;
        min-width: 140px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: var(--text);
        font-weight: 700;
        font-size: 13px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        transition: all 0.15s;
    }

    .quick-btn:hover {
        border-color: var(--blue);
        transform: translateY(-2px);
    }

    .quick-btn i {
        font-size: 16px;
        color: var(--blue);
    }

    /* ─── RESPONSIVE 2X2 MOBILE VIEW ─── */
    @media (max-width: 768px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px;
        }

        .stat-card {
            padding: 12px;
            min-height: auto;
        }

        .stat-icon-wrap {
            width: 36px;
            height: 36px;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 18px;
        }

        /* Detail item disembunyikan secara default di mobile view */
        .welcome-meta {
            display: none;
        }
        
        .welcome-meta.show-mobile {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
<div class="content-inner">
    <!-- Salam & Info User -->
    <div class="welcome-strip">
        <div class="welcome-top">
            <div>
                <div class="welcome-title">Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</div>
                <div class="welcome-sub">Ringkasan aktivitas transaksi hari ini.</div>
            </div>
            
            <!-- Tombol Kecil Toggle Info di Mobile -->
            <button type="button" class="btn-toggle-meta" onclick="toggleMetaInfo()">
                <i class="fas fa-info-circle"></i>
                <span id="btnMetaText">Info</span>
            </button>
        </div>

        <!-- Detail Meta (Tersembunyi di mobile kecuali tombol ditekan) -->
        <div class="welcome-meta" id="welcomeMetaBox">
            <span class="meta-pill">
                <i class="fas fa-user" style="font-size:10px;"></i>
                {{ Auth::user()->role === 'admin' ? 'Admin' : 'Kepala Cabang' }}
            </span>
            <span class="meta-pill">
                <i class="fas fa-building" style="font-size:10px;"></i>
                {{ Auth::user()->role === 'admin' ? 'Pusat (Semua Cabang)' : (Auth::user()->cabangRelasi ? Auth::user()->cabangRelasi->nama_cabang : (Auth::user()->cabang ?? 'Cabang')) }}
            </span>
            <span class="meta-pill">
                <i class="fas fa-clock" style="font-size:10px;"></i>
                {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB
            </span>
        </div>
    </div>

    <!-- 4 STAT CARDS (2x2 di Mobile View) -->
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-icon-wrap blue">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div>
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-value blue">{{ $transaksiHariIni }}</div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon-wrap red">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value red">{{ $belumLunas }}</div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon-wrap green">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <div class="stat-label">Omset Hari Ini</div>
                <div class="stat-value green">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card teal">
            <div class="stat-icon-wrap teal">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <div class="stat-label">Omset Bulan Ini</div>
                <div class="stat-value teal">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Shortcut Cepat -->
    <div class="quick-actions-bar">
        <a href="{{ route('transaksi.create') }}" class="quick-btn">
            <i class="fas fa-plus-circle" style="color: var(--blue);"></i>
            <span>Buat Transaksi Baru</span>
        </a>
        <a href="{{ route('transaksi.index') }}" class="quick-btn">
            <i class="fas fa-receipt" style="color: #0284c7;"></i>
            <span>Daftar Transaksi</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="quick-btn">
            <i class="fas fa-chart-bar" style="color: var(--green);"></i>
            <span>Lihat Laporan & Grafik</span>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleMetaInfo() {
        const metaBox = document.getElementById('welcomeMetaBox');
        const btnText = document.getElementById('btnMetaText');
        const isShown = metaBox.classList.toggle('show-mobile');
        btnText.textContent = isShown ? 'Tutup' : 'Info';
    }
</script>
@endpush
