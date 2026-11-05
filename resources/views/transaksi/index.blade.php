@extends('layouts.app')

@section('title', 'Data Transaksi')
@section('page-title', 'Catatan Transaksi')

@push('styles')
<style>
    .page-shell {
        max-width: 1180px;
        margin: 0 auto;
        padding-bottom: 24px;
    }

    .toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .btn-tambah {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: linear-gradient(135deg, #1e88e5, #1565c0);
        color: white;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(30,136,229,0.18);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .btn-tambah:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(30,136,229,0.22);
    }

    .search-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(15,23,36,0.06);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(11,35,60,0.04);
        padding: 8px;
    }

    .search-form input,
    .search-form select {
        border: 2px solid rgba(148,163,184,0.28);
        background: white;
        border-radius: 12px;
        padding: 12px 14px;
        font: inherit;
        font-weight: 700;
        font-size: 14px;
        color: var(--text);
        outline: none;
        min-height: 48px;
        cursor: pointer;
        box-sizing: border-box;
    }

    .search-form input { min-width: 200px; cursor: text; }
    .search-form select { min-width: 160px; }

    .search-form button {
        border: none;
        background: #0d1b2a;
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 800;
        min-height: 48px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .search-form button:hover {
        background: #1e293b;
    }

    .date-filter-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .date-filter-group select {
        padding: 12px 10px;
    }

    .date-filter-group #tanggalSelect { min-width: 110px; }
    .date-filter-group #bulanSelect   { min-width: 135px; }
    .date-filter-group #tahunSelect   { min-width: 110px; }

    .reset-link {
        color: #d93025;
        font-weight: 700;
        text-decoration: none;
        font-size: 13px;
        padding: 0 4px;
    }

    /* ─── DATA CONTAINER & TRANSITION ─── */
    #transaksi-data-container {
        position: relative;
        transition: opacity 0.2s ease, filter 0.2s ease;
    }

    #transaksi-data-container.is-loading {
        opacity: 0.45;
        filter: blur(1px);
        pointer-events: none;
    }

    .table-card {
        background: rgba(255,255,255,0.96);
        border-radius: 18px;
        border: 1px solid rgba(15,23,36,0.05);
        box-shadow: 0 10px 30px rgba(11,35,60,0.04);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .tabel-sederhana {
        width: 100%;
        min-width: 820px;
        border-collapse: collapse;
    }

    .tabel-sederhana th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        background: #f8fbff;
        border-bottom: 1px solid #edf3f9;
    }

    .tabel-sederhana td {
        padding: 14px 16px;
        font-size: 14px;
        color: var(--text);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .tabel-sederhana tbody tr:hover {
        background: #fafcff;
    }

    .nama-pembeli {
        font-weight: 800;
        font-size: 15px;
        color: var(--text);
    }

    .tgl-transaksi {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .ikan-info {
        font-weight: 750;
        color: #0f172a;
        font-size: 13.5px;
    }

    .ikan-item-pill {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 12.5px;
    }

    .ikan-name-txt {
        font-weight: 700;
        color: #1e293b;
    }

    .ikan-weight-txt {
        font-weight: 700;
        color: #2563eb;
        background: #eff6ff;
        padding: 1px 6px;
        border-radius: 5px;
        font-size: 11.5px;
        white-space: nowrap;
    }

    .ikan-total-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 800;
        color: #166534;
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        padding: 2px 8px;
        border-radius: 6px;
        margin-top: 3px;
        width: fit-content;
    }

    .total-harga {
        font-weight: 800;
        color: var(--text);
        white-space: nowrap;
    }

    .status-lunas,
    .status-dp,
    .status-belum {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.04em;
    }

    .status-lunas {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .status-dp {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-belum {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .sisa-bayar {
        font-size: 11px;
        color: var(--red);
        font-weight: 700;
        margin-top: 5px;
    }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
        margin-right: 6px;
    }

    .btn-edit { background: #ffb300; }
    .btn-hapus { background: var(--red); }

    .alert-sukses {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #edfdf2;
        color: #1b5e20;
        border: 1px solid #cfeecf;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 18px;
        font-weight: 600;
    }

    /* ─── TOMBOL SALIN NO TELEPON (CLIPBOARD) ─── */
    .btn-copy-phone {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 7px;
        padding: 3px 8px;
        margin-top: 3px;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-copy-phone:hover {
        background: #dbeafe;
        color: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-copy-phone:active {
        transform: scale(0.95);
    }

    .btn-copy-phone.copied {
        background: #ecfdf5 !important;
        color: #059669 !important;
        border-color: #a7f3d0 !important;
    }

    .btn-copy-phone i {
        font-size: 11px;
    }

    /* ─── TOAST NOTIFICATION ─── */
    .copy-toast-notification {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(40px);
        background: #0f172a;
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 99999;
        pointer-events: none;
    }

    .copy-toast-notification.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .copy-toast-notification i {
        color: #10b981;
    }

    /* ─── MOBILE CARDS ─── */
    .mobile-list {
        display: none;
    }

    .mobile-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 16px;
        margin-bottom: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .mobile-name {
        font-weight: 700;
        font-size: 16px;
        color: #111827;
        text-transform: capitalize;
        line-height: 1.25;
    }

    .mobile-date {
        font-size: 12px;
        color: #6B7280;
        margin-top: 3px;
    }

    .mobile-total {
        font-weight: 800;
        color: #111827;
        font-size: 16px;
        white-space: nowrap;
        text-align: right;
    }

    .mobile-weight {
        font-size: 12px;
        color: #6B7280;
        margin-top: 3px;
        text-align: right;
    }

    .mobile-items {
        margin: 12px 0;
        padding: 10px 0;
        border-top: 1px solid #f3f4f6;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .mobile-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }

    .mobile-item-name {
        color: #1F2937;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mobile-item-weight {
        color: #6B7280;
        font-family: monospace;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
        text-align: right;
    }

    .mobile-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding-top: 2px;
    }

    .mobile-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .mobile-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.1s, opacity 0.15s;
    }

    .mobile-btn:active {
        transform: scale(0.94);
    }

    .mobile-btn.receipt {
        background: #e0f2fe;
        color: #0284c7;
    }

    .mobile-btn.edit {
        background: #f59e0b;
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25);
    }

    .mobile-btn.delete {
        background: #ef4444;
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 180px;
        text-align: center;
        color: var(--text-muted);
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(15,23,36,0.05);
        border-radius: 18px;
        box-shadow: 0 8px 22px rgba(11,35,60,0.03);
        padding: 32px 18px;
    }

    .empty-state i {
        font-size: 38px;
        opacity: 0.35;
    }

    /* ─── FLUENT MINIMALIST PAGINATION ─── */
    .fluent-pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
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

    @media (max-width: 640px) {
        .toolbar {
            display: block;
        }
        .btn-tambah {
            width: 100%;
            margin-bottom: 12px;
        }
        .search-form {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .search-form input,
        .search-form select,
        .search-form button {
            width: 100%;
        }
        .date-filter-group {
            display: grid;
            grid-template-columns: 1fr 1.35fr 1fr;
            gap: 6px;
            width: 100%;
        }
        .date-filter-group select {
            min-width: 0 !important;
            font-size: 13px;
            padding: 10px 4px;
            min-height: 46px;
            text-align: center;
        }
        .table-card {
            display: none;
        }
        .mobile-list {
            display: block;
        }
        .fluent-pagination {
            gap: 4px;
            padding: 4px 6px;
        }
        .fluent-page-btn {
            min-width: 32px;
            height: 32px;
            font-size: 12px;
            padding: 0 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-shell">
    @if(session('success'))
    <div class="alert-sukses">
        <i class="fas fa-check-circle" style="font-size: 22px;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <div class="toolbar">
        @if(Auth::user()->role !== 'admin')
        <a href="{{ route('transaksi.create') }}" class="btn-tambah">
            <i class="fas fa-plus-circle"></i>
            <span>Buat Transaksi Baru</span>
        </a>
        @else
        <div style="display:inline-flex;align-items:center;gap:8px;background:#f1f5f9;color:#64748b;padding:12px 18px;border-radius:12px;font-size:14px;font-weight:700;">
            <i class="fas fa-eye"></i>
            <span>Mode Monitoring — Hanya Lihat Data</span>
        </div>
        @endif

        <form class="search-form" id="searchFilterForm" method="GET" action="{{ route('transaksi.index') }}">
            <input type="text" name="search" id="searchInput" placeholder="Cari nama atau no HP..." value="{{ $search ?? '' }}">
            
            @if(Auth::user()->role === 'admin')
            <select name="cabang" id="cabangSelect" onchange="this.form.requestSubmit()">
                <option value="">Semua Cabang</option>
                <option value="Suci" {{ ($cabang ?? '') == 'Suci' ? 'selected' : '' }}>Cabang Suci</option>
                <option value="Cilawu" {{ ($cabang ?? '') == 'Cilawu' ? 'selected' : '' }}>Cabang Cilawu</option>
                <option value="Samarang" {{ ($cabang ?? '') == 'Samarang' ? 'selected' : '' }}>Cabang Samarang</option>
            </select>
            @endif

            <select name="status" id="statusSelect" onchange="this.form.requestSubmit()">
                <option value="">Semua Status</option>
                <option value="lunas" {{ ($status ?? '') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum_lunas" {{ ($status ?? '') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas (termasuk DP)</option>
            </select>

            <!-- Group Tanggal, Bulan, Tahun Sejajar -->
            <div class="date-filter-group">
                <!-- Filter Tanggal (1-31) -->
                <select name="tanggal" id="tanggalSelect" onchange="this.form.requestSubmit()">
                    <option value="">Tanggal</option>
                    @for($d = 1; $d <= 31; $d++)
                        @php $dVal = str_pad($d, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $dVal }}" {{ ($tanggal ?? '') == $dVal || ($tanggal ?? '') == $d ? 'selected' : '' }}>Tgl {{ $dVal }}</option>
                    @endfor
                </select>

                <!-- Filter Bulan -->
                <select name="bulan" id="bulanSelect" onchange="this.form.requestSubmit()">
                    <option value="">Bulan</option>
                    @foreach($months as $mNum => $mName)
                        @php $mVal = str_pad($mNum, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $mVal }}" {{ ($bulan ?? '') == $mVal || ($bulan ?? '') == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                    @endforeach
                </select>

                <!-- Filter Tahun -->
                <select name="tahun" id="tahunSelect" onchange="this.form.requestSubmit()">
                    <option value="">Tahun</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ ($tahun ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"><i class="fas fa-search"></i> CARI</button>
            @if($search || $status || $tanggal || $bulan || $tahun || ($cabang ?? ''))
                <a href="{{ route('transaksi.index') }}" class="reset-link" id="resetFilterBtn">Reset</a>
            @endif
        </form>
    </div>

    <!-- ─── WRAPPER DATA TRANSAKSI UNTUK AJAX ─── -->
    <div id="transaksi-data-container">
        <!-- Tampilan Desktop: Tabel -->
        <div class="table-card desktop-table">
            <div class="table-wrapper">
                <table class="tabel-sederhana">
                    <thead>
                        <tr>
                            <th>Pembeli</th>
                            <th>Ikan</th>
                            <th>Total (Rp)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                        @php
                            $telepon = $t->telepon ?: ($t->pelanggan->telepon ?? null);
                        @endphp
                        <tr>
                            <td>
                                <div class="nama-pembeli">{{ $t->pembeli_nama }}</div>
                                @if($telepon)
                                    <div>
                                        <button type="button" class="btn-copy-phone" onclick="copyToClipboard('{{ $telepon }}', this)" title="Klik untuk salin no telepon">
                                            <i class="fas fa-copy"></i>
                                            <span>{{ $telepon }}</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="tgl-transaksi"><i class="fas fa-calendar-alt"></i> {{ $t->tanggal_transaksi->format('d M Y - H:i') }}</div>
                            </td>
                            <td>
                                @if($t->details && $t->details->count() > 0)
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        @foreach($t->details as $d)
                                            @php
                                                $namaIkan = $d->ikan ? $d->ikan->nama_ikan : ($d->nama_ikan ?: ($t->ikan ? $t->ikan->nama_ikan : 'Ikan'));
                                            @endphp
                                            <div class="ikan-item-pill">
                                                <span class="ikan-name-txt">{{ $namaIkan }}</span>
                                                <span class="ikan-weight-txt">{{ number_format($d->jumlah_kg, 2) }} Kg</span>
                                            </div>
                                        @endforeach
                                        @if($t->details->count() > 1)
                                            <div class="ikan-total-badge">
                                                <i class="fas fa-calculator" style="font-size: 10px;"></i>
                                                <span>Total: {{ number_format($t->jumlah_kg, 2) }} Kg</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    @php
                                        $namaIkan = $t->ikan ? $t->ikan->nama_ikan : 'Ikan';
                                    @endphp
                                    <div class="ikan-item-pill">
                                        <span class="ikan-name-txt">{{ $namaIkan }}</span>
                                        <span class="ikan-weight-txt">{{ number_format($t->jumlah_kg, 2) }} Kg</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="total-harga">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($t->status == 'lunas')
                                    <div class="status-lunas"><i class="fas fa-check"></i> LUNAS</div>
                                @elseif($t->status == 'dp')
                                    <div class="status-dp"><i class="fas fa-clock"></i> DP</div>
                                    <div class="sisa-bayar">Sisa: Rp {{ number_format($t->total - $t->jumlah_dibayar, 0, ',', '.') }}</div>
                                @else
                                    <div class="status-belum"><i class="fas fa-times"></i> BELUM</div>
                                    <div class="sisa-bayar">Tunggakan: Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('transaksi.show', $t->id) }}" class="btn-aksi" style="background: #e0f2fe; color: #0284c7;" title="Lihat Struk / WA"><i class="fas fa-receipt"></i></a>
                                <a href="{{ route('transaksi.edit', $t->id) }}" class="btn-aksi btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                                @if(Auth::user()->role !== 'kepala_cabang')
                                <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Transaksi?', 'Apakah Anda yakin ingin menghapus transaksi ini? Stok ikan akan dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-hapus" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <div>Belum ada data transaksi.</div>
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
            @php
                $telepon = $t->telepon ?: ($t->pelanggan->telepon ?? null);
            @endphp
            <div class="mobile-card">
                <!-- Bagian Atas: Kiri (Nama, Salin Telp, Tanggal), Kanan (Harga, Total Berat) -->
                <div class="mobile-head">
                    <div>
                        <div class="mobile-name">{{ $t->pembeli_nama ?: ($t->pelanggan->nama ?? 'Pelanggan') }}</div>
                        @if($telepon)
                            <div>
                                <button type="button" class="btn-copy-phone" onclick="copyToClipboard('{{ $telepon }}', this)" title="Klik untuk salin no telepon">
                                    <i class="fas fa-copy"></i>
                                    <span>{{ $telepon }}</span>
                                </button>
                            </div>
                        @endif
                        <div class="mobile-date">
                            {{ $t->tanggal_transaksi->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="mobile-total">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                        <div class="mobile-weight">
                            Total: <strong>{{ number_format($t->jumlah_kg, 2) }} Kg</strong>
                        </div>
                    </div>
                </div>

                <!-- Bagian Tengah: Daftar Ikan (Nama di Kiri - Gelap, Berat di Kanan - Biru/Rapi sejajar) -->
                <div class="mobile-items">
                    @if($t->details && $t->details->count() > 0)
                        @foreach($t->details as $d)
                            @php
                                $mNamaIkan = $d->ikan ? $d->ikan->nama_ikan : ($d->nama_ikan ?: ($t->ikan ? $t->ikan->nama_ikan : 'Ikan'));
                            @endphp
                            <div class="mobile-item-row">
                                <div class="mobile-item-name">
                                    <span>{{ $mNamaIkan }}</span>
                                </div>
                                <div class="mobile-item-weight" style="background: #f1f5f9; padding: 2px 7px; border-radius: 6px; font-weight: 700; color: #1e293b;">
                                    {{ number_format($d->jumlah_kg, 2) }} Kg
                                </div>
                            </div>
                        @endforeach
                    @else
                        @php
                            $mNamaIkan = $t->ikan ? $t->ikan->nama_ikan : 'Ikan';
                        @endphp
                        <div class="mobile-item-row">
                            <div class="mobile-item-name">
                                <span>{{ $mNamaIkan }}</span>
                            </div>
                            <div class="mobile-item-weight" style="background: #f1f5f9; padding: 2px 7px; border-radius: 6px; font-weight: 700; color: #1e293b;">
                                {{ number_format($t->jumlah_kg, 2) }} Kg
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Bagian Bawah: Status di Kiri & 3 Tombol Aksi di Kanan -->
                <div class="mobile-footer">
                    <div>
                        @if($t->status == 'lunas')
                            <span class="status-lunas"><i class="fas fa-check"></i> LUNAS</span>
                        @elseif($t->status == 'dp')
                            <span class="status-dp"><i class="fas fa-clock"></i> DP</span>
                        @else
                            <span class="status-belum"><i class="fas fa-times"></i> BELUM</span>
                        @endif
                    </div>
                    <div class="mobile-actions">
                        <a href="{{ route('transaksi.show', $t->id) }}" class="mobile-btn receipt" title="Lihat Struk / WA"><i class="fas fa-receipt"></i></a>
                        <a href="{{ route('transaksi.edit', $t->id) }}" class="mobile-btn edit" title="Edit"><i class="fas fa-pen"></i></a>
                        @if(Auth::user()->role !== 'kepala_cabang')
                        <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Transaksi?', 'Apakah Anda yakin ingin menghapus transaksi ini? Stok ikan akan dikembalikan.');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mobile-btn delete" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <div>Belum ada data transaksi.</div>
            </div>
            @endforelse
        </div>

        <!-- ─── PAGINATION FLUENT MINIMALIST (POSISI TENGAH) ─── -->
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
@endsection

@push('scripts')
<script>
    // ─── FUNGSI SALIN NO TELEPON KE CLIPBOARD & FEEDBACK ───
    function copyToClipboard(text, element) {
        if (!text) return;

        const doSuccessFeedback = () => {
            if (element) {
                const originalHTML = element.innerHTML;
                element.classList.add('copied');
                element.innerHTML = `<i class="fas fa-check"></i> <span>Tersalin!</span>`;
                setTimeout(() => {
                    element.innerHTML = originalHTML;
                    element.classList.remove('copied');
                }, 1800);
            }
            showToast('Nomor ' + text + ' berhasil disalin!');
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text)
                .then(doSuccessFeedback)
                .catch(() => fallbackCopy(text, doSuccessFeedback));
        } else {
            fallbackCopy(text, doSuccessFeedback);
        }
    }

    function fallbackCopy(text, callback) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (callback) callback();
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(textArea);
    }

    function showToast(message) {
        let toast = document.getElementById('copy-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'copy-toast';
            toast.className = 'copy-toast-notification';
            document.body.appendChild(toast);
        }
        toast.innerHTML = `<i class="fas fa-check-circle"></i> <span>${message}</span>`;
        toast.classList.add('show');
        clearTimeout(window.copyToastTimeout);
        window.copyToastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 2000);
    }

    // ─── AJAX PAGINATION & FILTER TANPA REFRESH HALAMAN ───
    async function loadTransaksiData(url, updateHistory = true) {
        const container = document.getElementById('transaksi-data-container');
        if (!container) return;

        // Beri efek loading halus
        container.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Gagal mengambil data transaksi');

            const htmlText = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            const newContent = doc.getElementById('transaksi-data-container');

            if (newContent) {
                container.innerHTML = newContent.innerHTML;
            }

            // Update browser URL tanpa refresh
            if (updateHistory) {
                history.pushState(null, '', url);
            }

            // Scroll halus ke atas tabel
            const toolbar = document.querySelector('.toolbar');
            if (toolbar) {
                toolbar.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } catch (error) {
            console.error('AJAX Error:', error);
            // Fallback reload jika terjadi masalah
            window.location.href = url;
        } finally {
            container.classList.remove('is-loading');
        }
    }

    // Tangkap klik tombol pagination
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.fluent-page-btn[href]');
        if (link) {
            e.preventDefault();
            loadTransaksiData(link.href);
        }

        const resetLink = e.target.closest('#resetFilterBtn');
        if (resetLink) {
            e.preventDefault();
            const searchIn = document.getElementById('searchInput');
            if (searchIn) searchIn.value = '';
            const statusSel = document.getElementById('statusSelect');
            if (statusSel) statusSel.value = '';
            const tglSel = document.getElementById('tanggalSelect');
            if (tglSel) tglSel.value = '';
            const blnSel = document.getElementById('bulanSelect');
            if (blnSel) blnSel.value = '';
            const thnSel = document.getElementById('tahunSelect');
            if (thnSel) thnSel.value = '';
            loadTransaksiData(resetLink.href);
        }
    });

    // Tangkap submit form pencarian / filter
    const searchForm = document.getElementById('searchFilterForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);
            const actionUrl = searchForm.action + '?' + params.toString();
            loadTransaksiData(actionUrl);
        });
    }

    // Support tombol Back/Forward browser
    window.addEventListener('popstate', function () {
        loadTransaksiData(window.location.href, false);
    });
</script>
@endpush
