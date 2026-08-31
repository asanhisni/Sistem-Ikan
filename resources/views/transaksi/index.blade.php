@extends('layouts.app')

@section('title', 'Data Transaksi')
@section('page-title', 'Catatan Transaksi')

@push('styles')
<style>
    .page-shell {
        max-width: 1180px;
        margin: 0 auto;
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
        background: rgba(255,255,255,0.92);
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
    }
        cursor: pointer;
    }

    .reset-link {
        color: #d93025;
        font-weight: 700;
        text-decoration: none;
        font-size: 13px;
        padding: 0 4px;
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
        font-weight: 700;
        color: var(--blue);
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
        background: var(--green-light);
        color: var(--green);
    }

    .status-dp {
        background: #fff4d6;
        color: #c77c00;
    }

    .status-belum {
        background: var(--red-light);
        color: var(--red);
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
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .mobile-name {
        font-weight: 800;
        font-size: 15px;
        color: var(--text);
    }

    .mobile-total {
        font-weight: 800;
        color: var(--blue);
        font-size: 14px;
        white-space: nowrap;
    }

    .mobile-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px;
        color: var(--text-muted);
        font-size: 12px;
        margin-bottom: 10px;
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
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        color: white;
        text-decoration: none;
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
        padding: 32px 18px;
    }

    .empty-state i {
        font-size: 38px;
        opacity: 0.35;
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
        <a href="{{ route('transaksi.create') }}" class="btn-tambah">
            <i class="fas fa-plus-circle"></i>
            <span>Buat Transaksi Baru</span>
        </a>

        <form class="search-form" method="GET" action="{{ route('transaksi.index') }}">
            <input type="text" name="search" placeholder="Cari nama pembeli..." value="{{ $search ?? '' }}">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="lunas" {{ ($status ?? '') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum_lunas" {{ ($status ?? '') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
            <button type="submit">CARI</button>
            @if($search || $status)
                <a href="{{ route('transaksi.index') }}" class="reset-link">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-card desktop-table">
        <div class="table-wrapper">
            <table class="tabel-sederhana">
                <thead>
                    <tr>
                        <th>Pembeli & Waktu</th>
                        <th>Pesanan Ikan</th>
                        <th>Total Harga</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr>
                        <td>
                            <div class="nama-pembeli">{{ $t->pembeli_nama }}</div>
                            @if($t->telepon)
                                <div style="font-size: 12px; color: #0284c7; font-weight: 600; margin-top: 2px;">
                                    <i class="fas fa-phone-alt" style="font-size: 10px;"></i> {{ $t->telepon }}
                                </div>
                            @endif
                            <div class="tgl-transaksi"><i class="fas fa-calendar-alt"></i> {{ $t->tanggal_transaksi->format('d M Y - H:i') }}</div>
                        </td>
                        <td>
                            @if($t->details && $t->details->count() > 0)
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    @foreach($t->details as $d)
                                        <div>
                                            <span class="ikan-info">{{ $d->ikan ? $d->ikan->nama_ikan : '-' }}</span>
                                            <span style="font-size: 13px; color: var(--text-muted);">({{ $d->jumlah_kg }} Kg)</span>
                                        </div>
                                    @endforeach
                                    @if($t->details->count() > 1)
                                        <div style="font-size: 11px; font-weight: 700; color: #166534; margin-top: 2px;">
                                            Total: {{ $t->jumlah_kg }} Kg
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="ikan-info">{{ $t->ikan ? $t->ikan->nama_ikan : '-' }}</div>
                                <div>{{ $t->jumlah_kg }} Kg</div>
                            @endif
                        </td>
                        <td>
                            <div class="total-harga">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            @if($t->status == 'lunas')
                                <div class="status-lunas"><i class="fas fa-check"></i> LUNAS</div>
                            @elseif($t->status == 'dp')
                                <div class="status-dp"><i class="fas fa-hand-holding-usd"></i> DP</div>
                                <div class="sisa-bayar">Sisa: Rp {{ number_format($t->total - $t->jumlah_dibayar, 0, ',', '.') }}</div>
                            @else
                                <div class="status-belum"><i class="fas fa-times"></i> BELUM</div>
                                <div class="sisa-bayar">Tunggakan: Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('transaksi.show', $t->id) }}" class="btn-aksi" style="background: #e0f2fe; color: #0284c7;" title="Lihat Struk / WA"><i class="fas fa-receipt"></i></a>
                            <a href="{{ route('transaksi.edit', $t->id) }}" class="btn-aksi btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi btn-hapus" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
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

    <div class="mobile-list">
        @forelse($transaksi as $t)
        <div class="mobile-card">
            <div class="mobile-head">
                <div>
                    <div class="mobile-name">{{ $t->pembeli_nama }}</div>
                    @if($t->telepon)
                        <div style="font-size: 12px; color: #0284c7; font-weight: 600; margin-top: 2px;">
                            <i class="fas fa-phone-alt" style="font-size: 10px;"></i> {{ $t->telepon }}
                        </div>
                    @endif
                </div>
                <div class="mobile-total">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
            </div>
            <div class="mobile-meta">
                <span>{{ $t->tanggal_transaksi->format('d M Y') }}</span>
                <span>Total: {{ $t->jumlah_kg }} Kg</span>
            </div>
            <div class="mobile-meta" style="margin-bottom: 12px; display: flex; flex-direction: column; gap: 4px;">
                @if($t->details && $t->details->count() > 0)
                    <div style="font-size: 13px;">
                        @foreach($t->details as $d)
                            <div>&bull; <span class="ikan-info">{{ $d->ikan ? $d->ikan->nama_ikan : '-' }}</span> ({{ $d->jumlah_kg }} Kg)</div>
                        @endforeach
                    </div>
                @else
                    <span class="ikan-info">{{ $t->ikan ? $t->ikan->nama_ikan : '-' }}</span>
                @endif
                <div style="margin-top: 6px;">
                    @if($t->status == 'lunas')
                        <span class="status-lunas"><i class="fas fa-check"></i> LUNAS</span>
                    @elseif($t->status == 'dp')
                        <span class="status-dp"><i class="fas fa-hand-holding-usd"></i> DP</span>
                    @else
                        <span class="status-belum"><i class="fas fa-times"></i> BELUM</span>
                    @endif
                </div>
            </div>
            <div class="mobile-actions">
                <a href="{{ route('transaksi.show', $t->id) }}" class="mobile-btn" style="background: #e0f2fe; color: #0284c7;" title="Lihat Struk / WA"><i class="fas fa-receipt"></i></a>
                <a href="{{ route('transaksi.edit', $t->id) }}" class="mobile-btn edit" title="Edit"><i class="fas fa-pen"></i></a>
                <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="mobile-btn delete" title="Hapus"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <div>Belum ada data transaksi.</div>
        </div>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $transaksi->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
