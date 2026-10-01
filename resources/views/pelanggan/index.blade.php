@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page-title', 'Daftar Pelanggan')

@push('styles')
<style>
    .page-shell {
        max-width: 1100px;
        margin: 0 auto;
    }

    .toolbar {
        margin-bottom: 18px;
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
        margin-bottom: 18px;
        font-weight: 600;
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
        min-width: 760px;
        border-collapse: collapse;
    }

    .tabel-sederhana th {
        text-align: left;
        padding: 12px 16px;
        background: #f8fbff;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 1px solid #edf3f9;
    }

    .tabel-sederhana td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text);
        font-size: 14px;
        vertical-align: middle;
    }

    .tabel-sederhana tbody tr:hover {
        background: #fafcff;
    }

    .nama-item {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
    }

    .info-tambahan {
        font-size: 13px;
        color: var(--text-muted);
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

    .mobile-list {
        display: none;
    }

    .mobile-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(15,23,36,0.06);
        border-radius: 18px;
        box-shadow: 0 10px 24px rgba(11,35,60,0.04);
        padding: 14px;
        margin-bottom: 12px;
    }

    .mobile-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
    }

    .mobile-name {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
    }

    .mobile-meta {
        display: flex;
        flex-direction: column;
        gap: 6px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .mobile-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .mobile-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .mobile-btn.edit { background: #ffb300; }
    .mobile-btn.delete { background: var(--red); }

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
        padding: 24px 18px;
    }

    .empty-state i {
        font-size: 38px;
        opacity: 0.35;
    }

    @media (max-width: 640px) {
        .btn-tambah {
            width: 100%;
        }
        .table-card {
            display: none;
        }
        .mobile-list {
            display: block;
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
        <a href="{{ route('pelanggan.create') }}" class="btn-tambah">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Pelanggan</span>
        </a>
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table class="tabel-sederhana">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>No. HP / Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggan as $p)
                    <tr>
                        <td>
                            <div class="nama-item">{{ $p->nama }}</div>
                        </td>
                        <td>
                            <div class="info-tambahan"><i class="fas fa-phone"></i> {{ $p->telepon ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="info-tambahan">{{ $p->alamat ?? '-' }}</div>
                        </td>
                        <td>
                            <a href="{{ route('pelanggan.edit', $p->id) }}" class="btn-aksi btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('pelanggan.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Data Pelanggan?', 'Apakah Anda yakin ingin menghapus pelanggan {{ addslashes($p->nama) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi btn-hapus" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <div>Belum ada data pelanggan.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mobile-list">
        @forelse($pelanggan as $p)
        <div class="mobile-card">
            <div class="mobile-head">
                <div class="mobile-name">{{ $p->nama }}</div>
            </div>
            <div class="mobile-meta">
                <span><i class="fas fa-phone"></i> {{ $p->telepon ?? '-' }}</span>
                <span><i class="fas fa-map-marker-alt"></i> {{ $p->alamat ?? '-' }}</span>
            </div>
            <div class="mobile-actions">
                <a href="{{ route('pelanggan.edit', $p->id) }}" class="mobile-btn edit" title="Edit"><i class="fas fa-pen"></i></a>
                <form action="{{ route('pelanggan.destroy', $p->id) }}" method="POST" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Data Pelanggan?', 'Apakah Anda yakin ingin menghapus pelanggan {{ addslashes($p->nama) }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="mobile-btn delete" title="Hapus"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <div>Belum ada data pelanggan.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
