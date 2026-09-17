@extends('layouts.app')

@section('title', 'Laporan Stok Ikan')
@section('page-title', 'Laporan Stok Ikan')

@push('styles')
<style>
    /* ─── CONTAINER UTAMA LAPORAN STOK ─── */
    .stok-page-shell {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* ─── 1. TAB SWITCHER ─── */
    .laporan-nav-tabs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #e2e8f0;
        padding: 4px;
        border-radius: 14px;
        gap: 4px;
    }

    .laporan-tab-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .laporan-tab-btn:hover {
        color: #1e293b;
    }

    .laporan-tab-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }

    .laporan-tab-btn.active i {
        color: #2563eb;
    }

    /* ─── 2. FILTER CARD ─── */
    .filter-section-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 14px 16px;
        border: 1px solid rgba(226, 232, 240, 0.85);
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    }

    .filter-inputs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .filter-group label i {
        color: #2563eb;
        font-size: 12px;
    }

    .filter-input-wrap {
        position: relative;
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

    /* ─── 3. CARD RINGKASAN (TOTAL STOK & JENIS IKAN - SEJAJAR & SAMA TINGGI) ─── */
    .stok-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 14px;
        align-items: stretch;
    }

    .stok-summary-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 84px;
        box-sizing: border-box;
    }

    .stok-summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stok-summary-icon.icon-blue {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .stok-summary-icon.icon-green {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
    }

    .stok-summary-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        overflow: hidden;
    }

    .stok-summary-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 3px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stok-summary-val {
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ─── 4. SECTION DAFTAR STOK HEADER ─── */
    .stok-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 0 2px;
    }

    .stok-list-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
    }

    .stok-list-title-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .live-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: #f1f5f9;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        animation: livePulse 2s infinite;
    }

    @keyframes livePulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
    }

    /* ─── 5. FLOATING FISH CARDS (CARD PUTIH ROUNDED + SHADOW SOFT) ─── */
    .stok-cards-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .floating-fish-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .floating-fish-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.07);
    }

    /* Background khusus jika Stok Menipis (Kuning Muda) */
    .floating-fish-card.card-menipis {
        background: #fffdf0;
        border: 1.5px solid #fde047;
        box-shadow: 0 4px 16px rgba(234, 179, 8, 0.08);
    }

    /* Background khusus jika Habis (Merah Muda) */
    .floating-fish-card.card-habis {
        background: #fff9f9;
        border: 1.5px solid #fecaca;
    }

    /* Baris 1: Header (Icon + Nama Ikan + Badge Status) */
    .fish-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .fish-identity {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .fish-avatar {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .card-menipis .fish-avatar {
        background: #fef08a;
        border-color: #fde047;
    }

    .card-habis .fish-avatar {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .fish-name {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Badge Status */
    .badge-status-stok {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .badge-status-stok.aman {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .badge-status-stok.menipis {
        background: #fef08a;
        color: #854d0e;
        border: 1px solid #fde047;
    }

    .badge-status-stok.habis {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    /* Baris 2: Dua Kolom Sejajar (Stok Tersedia & Harga / Kg) */
    .fish-card-grid-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        width: 100%;
    }

    .fish-stat-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 62px;
        box-sizing: border-box;
        width: 100%;
    }

    .card-menipis .fish-stat-box {
        background: #ffffff;
        border-color: #fef08a;
    }

    .card-habis .fish-stat-box {
        background: #ffffff;
        border-color: #fee2e2;
    }

    .stat-box-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-align: left;
        line-height: 1.2;
        margin-bottom: 4px;
        display: block;
    }

    .fish-stat-box.stat-box-right .stat-box-label,
    .stat-box-label.text-right {
        text-align: right;
        margin-left: auto;
    }

    .stat-box-val {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
        display: block;
    }

    .stat-box-val.highlight-blue {
        color: #2563eb;
        font-weight: 800;
    }

    .fish-stat-box.stat-box-right .stat-box-val,
    .stat-box-val.text-right {
        text-align: right;
        margin-left: auto;
    }

    /* Baris 3: Total Nilai Lebih Menonjol (Sejajar Rata Kanan Presisi dengan Harga / Kg) */
    .fish-card-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
        width: 100%;
        min-height: 46px;
    }

    .card-menipis .fish-card-total-row {
        background: #fef9c3;
        border-color: #fde047;
    }

    .card-habis .fish-card-total-row {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .total-row-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 6px;
        text-align: left;
        line-height: 1.2;
        flex-shrink: 0;
    }

    .total-row-label .text-amber {
        color: #d97706;
        font-size: 13px;
    }

    .total-row-val {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: right;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
        margin-left: auto;
    }

    /* EMPTY STATE */
    .stok-empty-state {
        background: #ffffff;
        border-radius: 16px;
        padding: 36px 20px;
        text-align: center;
        border: 1.5px dashed #cbd5e1;
        color: #64748b;
    }

    .stok-empty-state i {
        font-size: 36px;
        color: #94a3b8;
        margin-bottom: 12px;
    }

    /* LOADING OVERLAY STATE */
    #stok-main-content {
        position: relative;
        transition: opacity 0.2s ease;
    }

    #stok-main-content.is-loading {
        opacity: 0.5;
        pointer-events: none;
    }

    /* RESPONSIVE BREAKPOINTS */
    @media (max-width: 560px) {
        .filter-inputs-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .stok-summary-card {
            padding: 12px;
            gap: 10px;
        }

        .stok-summary-icon {
            width: 40px;
            height: 40px;
            font-size: 17px;
            border-radius: 12px;
        }

        .stok-summary-label {
            font-size: 10px;
        }

        .stok-summary-val {
            font-size: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="stok-page-shell">

    <!-- 1. TAB SWITCHER (LAPORAN PENJUALAN & LAPORAN STOK IKAN) -->
    <div class="laporan-nav-tabs">
        <a href="{{ route('laporan.index') }}" class="laporan-tab-btn">
            <i class="fas fa-chart-line"></i>
            <span>Laporan Penjualan</span>
        </a>
        <a href="{{ route('laporan.stok') }}" class="laporan-tab-btn active">
            <i class="fas fa-boxes-stacked"></i>
            <span>Laporan Stok Ikan</span>
        </a>
    </div>

    <!-- 2. SECTION FILTER (DROPDOWN BULAN & CABANG) -->
    <div class="filter-section-card">
        <form method="GET" action="{{ route('laporan.stok') }}" class="filter-stok-form" id="stokFilterForm">
            <div class="filter-inputs-grid">
                <!-- Dropdown Pilih Bulan -->
                <div class="filter-group">
                    <label for="stokFilterBulan">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Pilih Bulan</span>
                    </label>
                    <div class="filter-input-wrap">
                        <select name="bulan" id="stokFilterBulan" class="filter-select">
                            @for($m = 0; $m < 12; $m++)
                                @php $mo = now()->subMonths($m)->format('Y-m'); @endphp
                                <option value="{{ $mo }}" {{ ($bulan ?? date('Y-m')) === $mo ? 'selected' : '' }}>
                                    {{ now()->subMonths($m)->isoFormat('MMMM YYYY') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                @if(Auth::user()->role === 'admin')
                <!-- Dropdown Pilih Cabang (Khusus Admin) -->
                <div class="filter-group">
                    <label for="stokFilterCabang">
                        <i class="fas fa-store"></i>
                        <span>Pilih Cabang</span>
                    </label>
                    <div class="filter-input-wrap">
                        <select name="cabang" id="stokFilterCabang" class="filter-select">
                            <option value="semua" {{ ($cabang ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Cabang</option>
                            <option value="Suci" {{ ($cabang ?? '') === 'Suci' ? 'selected' : '' }}>Suci</option>
                            <option value="Cilawu" {{ ($cabang ?? '') === 'Cilawu' ? 'selected' : '' }}>Cilawu</option>
                            <option value="Samarang" {{ ($cabang ?? '') === 'Samarang' ? 'selected' : '' }}>Samarang</option>
                        </select>
                    </div>
                </div>
                @else
                <!-- Lokasi Cabang Tetap (Khusus Kepala Cabang) -->
                <div class="filter-group">
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
            </div>
        </form>
    </div>

    <!-- MAIN CONTAINER UNTUK REALTIME AJAX UPDATE -->
    <div id="stok-main-content">
        <!-- 3. CARD RINGKASAN (TOTAL STOK TERSEDIA & JENIS IKAN) -->
        <div class="stok-summary-grid">
            <!-- Card Kiri: Total Stok Tersedia (Icon Kotak Biru) -->
            <div class="stok-summary-card">
                <div class="stok-summary-icon icon-blue">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div class="stok-summary-content">
                    <div class="stok-summary-label">TOTAL STOK TERSEDIA</div>
                    <div class="stok-summary-val">{{ number_format($totalStokKg, 2, ',', '.') }} Kg</div>
                </div>
            </div>

            <!-- Card Kanan: Jenis Ikan (Icon Ikan Hijau) -->
            <div class="stok-summary-card">
                <div class="stok-summary-icon icon-green">
                    <i class="fas fa-fish"></i>
                </div>
                <div class="stok-summary-content">
                    <div class="stok-summary-label">JENIS IKAN</div>
                    <div class="stok-summary-val">{{ $totalJenis }} Jenis</div>
                </div>
            </div>
        </div>

        <!-- 4. SECTION DAFTAR STOK IKAN REALTIME -->
        <div class="stok-list-header">
            <div class="stok-list-title">
                <span class="stok-list-title-icon">
                    <i class="fas fa-list-check"></i>
                </span>
                <span>Daftar Stok Ikan Realtime</span>
            </div>
        </div>

        <!-- DAFTAR ITEM IKAN (CARD PUTIH ROUNDED DENGAN SHADOW SOFT) -->
        <div class="stok-cards-container">
            @forelse($ikanList as $ikan)
                @php
                    $stok = (float) $ikan->stok_kg;
                    $totalNilai = $stok * (float) $ikan->harga_per_kg;

                    // Klasifikasi Status Badge & Card: Aman (Hijau > 10), Menipis (Kuning > 0 dan <= 10), Habis (Merah <= 0)
                    if ($stok > 10) {
                        $statusClass = 'aman';
                        $statusLabel = 'Stok Aman';
                        $statusIcon  = 'fa-check-circle';
                        $cardBgClass = '';
                    } elseif ($stok > 0) {
                        $statusClass = 'menipis';
                        $statusLabel = 'Stok Menipis';
                        $statusIcon  = 'fa-triangle-exclamation';
                        $cardBgClass = 'card-menipis';
                    } else {
                        $statusClass = 'habis';
                        $statusLabel = 'Habis';
                        $statusIcon  = 'fa-circle-xmark';
                        $cardBgClass = 'card-habis';
                    }
                @endphp
                <div class="floating-fish-card {{ $cardBgClass }}">
                    <!-- Baris 1: Icon Ikan + Nama Ikan + Badge Status di Kanan -->
                    <div class="fish-card-header">
                        <div class="fish-identity">
                            <div class="fish-avatar">
                                🐟
                            </div>
                            <div class="fish-name">{{ $ikan->nama_ikan }}</div>
                        </div>

                        <div class="fish-badge-wrap">
                            <span class="badge-status-stok {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                <span>{{ $statusLabel }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Baris 2: Dua Kolom Sejajar (Stok Tersedia & Harga / Kg) -->
                    <div class="fish-card-grid-row">
                        <div class="fish-stat-box">
                            <div class="stat-box-label">Stok Tersedia</div>
                            <div class="stat-box-val highlight-blue">{{ number_format($stok, 2, ',', '.') }} Kg</div>
                        </div>
                        <div class="fish-stat-box stat-box-right">
                            <div class="stat-box-label">Harga / Kg</div>
                            <div class="stat-box-val text-right">Rp {{ number_format($ikan->harga_per_kg, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- Baris 3: Total Nilai Lebih Menonjol (Bold / Lebih Besar, Sejajar Rata Kanan) -->
                    <div class="fish-card-total-row">
                        <div class="total-row-label">
                            <i class="fas fa-coins text-amber"></i>
                            <span>Total Nilai</span>
                        </div>
                        <div class="total-row-val text-right">
                            Rp {{ number_format($totalNilai, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="stok-empty-state">
                    <i class="fas fa-fish"></i>
                    <div style="font-weight: 700; color: #0f172a; font-size: 15px; margin-bottom: 4px;">Data Stok Ikan Tidak Ditemukan</div>
                    <div style="font-size: 13px;">Tidak ada data ikan untuk kriteria pencarian ini.</div>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // ─── AJAX REALTIME FILTER & UPDATE STOK ───
    async function loadStokData(url, isBackground = false, updateHistory = true) {
        const mainContainer = document.getElementById('stok-main-content');
        if (!mainContainer) return;

        if (!isBackground) {
            mainContainer.classList.add('is-loading');
        }

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Gagal memuat data stok');

            const htmlText = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            const newContent = doc.getElementById('stok-main-content');

            if (newContent) {
                mainContainer.innerHTML = newContent.innerHTML;
            }

            if (updateHistory && !isBackground) {
                history.pushState(null, '', url);
            }
        } catch (error) {
            console.error('AJAX Stok Error:', error);
            if (!isBackground) {
                window.location.href = url;
            }
        } finally {
            if (!isBackground) {
                mainContainer.classList.remove('is-loading');
            }
        }
    }

    // Fungsi Trigger Filter Otomatis
    function executeStokFilter() {
        const form = document.getElementById('stokFilterForm');
        if (!form) return;

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const actionUrl = form.action + '?' + params.toString();
        loadStokData(actionUrl);
    }

    // Auto-switch instan saat dropdown Bulan atau Cabang berubah
    const bulanSelect = document.getElementById('stokFilterBulan');
    if (bulanSelect) {
        bulanSelect.addEventListener('change', executeStokFilter);
    }

    const cabangSelect = document.getElementById('stokFilterCabang');
    if (cabangSelect) {
        cabangSelect.addEventListener('change', executeStokFilter);
    }

    // ─── LIVE REALTIME SILENT POLLING (Background Sync Setiap 10 Detik) ───
    setInterval(() => {
        if (!document.hidden) {
            loadStokData(window.location.href, true, false);
        }
    }, 10000);

    // Auto refresh saat tab kembali aktif
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            loadStokData(window.location.href, true, false);
        }
    });

    // Support Navigasi Browser Back / Forward
    window.addEventListener('popstate', function() {
        loadStokData(window.location.href, false, false);
    });
</script>
@endpush
