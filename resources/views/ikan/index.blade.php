@extends('layouts.app')

@section('title', 'Data Ikan')
@section('page-title', 'Daftar Ikan & Stok')

@push('styles')
<style>
    .page-shell {
        max-width: 1040px;
        margin: 0 auto;
        padding-bottom: 24px;
    }

    .toolbar {
        margin-bottom: 16px;
    }

    .btn-tambah {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-tambah:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    }

    .btn-tambah:active {
        transform: scale(0.98);
    }

    .alert-sukses {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #edfdf2;
        color: #1b5e20;
        border: 1px solid #cfeecf;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 16px;
        font-weight: 700;
        font-size: 13.5px;
    }

    /* ─── DESKTOP TABLE CARD ─── */
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.85);
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .tabel-sederhana {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }

    .tabel-sederhana th {
        text-align: left;
        padding: 12px 16px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid #eef2f6;
    }

    .tabel-sederhana td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #0f172a;
        font-size: 14px;
        vertical-align: middle;
    }

    .tabel-sederhana tbody tr:hover {
        background: #f8fafc;
    }

    .nama-item {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .harga-item {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .stok-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 12px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .stok-badge.stok-rendah {
        background: #ffe4e6;
        color: #9f1239;
        border: 1px solid rgba(244, 63, 94, 0.25);
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
        font-size: 13px;
        transition: transform 0.1s, opacity 0.15s, box-shadow 0.15s;
    }

    .btn-aksi:active {
        transform: scale(0.94);
    }

    .btn-edit {
        background: #f59e0b;
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25);
    }
    .btn-edit:hover {
        background: #d97706;
    }

    .btn-hapus {
        background: #ef4444;
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
    }
    .btn-hapus:hover {
        background: #dc2626;
    }

    /* ─── MOBILE LIST & FLOATING CARDS ─── */
    .mobile-list {
        display: none;
        flex-direction: column;
        gap: 12px;
    }

    .floating-ikan-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.85);
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        padding: 12px 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .floating-ikan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.07);
    }

    .ikan-row-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .ikan-nama-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .ikan-row-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .ikan-harga-val {
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
    }

    .ikan-action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 160px;
        text-align: center;
        color: #64748b;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 24px 18px;
    }

    .empty-state i {
        font-size: 34px;
        color: #94a3b8;
    }

    @media (max-width: 640px) {
        .table-card {
            display: none;
        }
        .mobile-list {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
<div class="page-shell">
    @if(session('success'))
    <div class="alert-sukses">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(!empty($namaCabang))
    <div style="margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; background: #ffffff; padding: 10px 14px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #1e293b;">
            <i class="fas fa-store" style="color: #2563eb;"></i>
            <span>Stok Ikan Cabang: <strong>{{ $namaCabang }}</strong></span>
        </div>
        <span style="font-size: 11px; font-weight: 700; background: #eff6ff; color: #2563eb; padding: 3px 8px; border-radius: 6px; border: 1px solid #dbeafe;">Kepala Cabang</span>
    </div>
    @endif

    <!-- TOMBOL TAMBAH IKAN FULL WIDTH -->
    <div class="toolbar">
        <a href="{{ route('ikan.create') }}" class="btn-tambah">
            <i class="fas fa-plus"></i>
            <span>Tambah Ikan</span>
        </a>
    </div>

    <!-- TAMPILAN DESKTOP TABLE -->
    <div class="table-card">
        <div class="table-wrapper">
            <table class="tabel-sederhana">
                <thead>
                    <tr>
                        <th>Nama Ikan</th>
                        <th>Harga Per Kg</th>
                        <th>Sisa Stok (Kg)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ikan as $i)
                    <tr>
                        <td>
                            <div class="nama-item">{{ $i->nama_ikan }}</div>
                        </td>
                        <td>
                            <div class="harga-item">Rp {{ number_format($i->harga_per_kg, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            <div class="stok-badge {{ $i->stok_kg <= 10 ? 'stok-rendah' : '' }}">
                                {{ number_format($i->stok_kg, 1, ',', '') }}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('ikan.edit', $i->id) }}" class="btn-aksi btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('ikan.destroy', $i->id) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Data Ikan?', 'Apakah Anda yakin ingin menghapus data ikan {{ addslashes($i->nama_ikan) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi btn-hapus" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-fish"></i>
                                <div>Belum ada data ikan.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAMPILAN MOBILE: KARTU MENGAMBANG PADAT & RAPI -->
    <div class="mobile-list">
        @forelse($ikan as $i)
        <div class="floating-ikan-card">
            <!-- Baris Atas: Nama Ikan (Kiri) & Badge Stok (Kanan) -->
            <div class="ikan-row-top">
                <div class="ikan-nama-title">{{ $i->nama_ikan }}</div>
            </div>

            <!-- Baris Bawah: Harga (Kiri) & Tombol Edit/Hapus (Kanan) -->
            <div class="ikan-row-bottom">
                <div class="ikan-harga-val">
                    Rp {{ number_format($i->harga_per_kg, 0, ',', '.') }}
                </div>
                <div class="ikan-action-buttons">
                    <a href="{{ route('ikan.edit', $i->id) }}" class="btn-aksi btn-edit" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('ikan.destroy', $i->id) }}" method="POST" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Data Ikan?', 'Apakah Anda yakin ingin menghapus data ikan {{ addslashes($i->nama_ikan) }}?');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-aksi btn-hapus" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-fish"></i>
            <div>Belum ada data ikan.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection

