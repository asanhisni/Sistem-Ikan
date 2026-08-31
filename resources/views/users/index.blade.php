@extends('layouts.app')

@section('title', 'Manajemen Kepala Cabang')
@section('page-title', 'Manajemen Kepala Cabang')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header-left h2 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
    }
    .page-header-left p {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .btn-tambah {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        background: linear-gradient(135deg, #1e88e5, #42a5f5);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(30,136,229,0.28);
        transition: all 0.2s;
    }
    .btn-tambah:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(30,136,229,0.38);
        color: #fff;
    }

    /* Alert */
    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background: var(--green-light); color: var(--green); border: 1px solid rgba(67,160,71,0.2); }
    .alert-danger  { background: var(--red-light);   color: var(--red);   border: 1px solid rgba(229,57,53,0.2); }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        padding: 20px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-icon.blue  { background: var(--blue-light); color: var(--blue); }
    .stat-icon.green { background: var(--green-light); color: var(--green); }
    .stat-icon.red   { background: var(--red-light); color: var(--red); }
    .stat-val  { font-size: 26px; font-weight: 800; color: var(--text); line-height: 1; }
    .stat-label{ font-size: 12px; color: var(--text-muted); margin-top: 3px; font-weight: 600; }

    /* Table Card */
    .table-card {
        padding: 0;
        overflow: hidden;
    }
    .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .table-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-card-title i { color: var(--blue); }
    .cari-box {
        position: relative;
    }
    .cari-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 13px;
    }
    .cari-box input {
        padding: 9px 14px 9px 34px;
        border-radius: 10px;
        border: 1px solid var(--border);
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        color: var(--text);
        width: 220px;
        transition: border-color 0.2s;
    }
    .cari-box input:focus {
        outline: none;
        border-color: var(--blue);
    }

    table { width: 100%; border-collapse: collapse; }
    thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--text-muted);
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    tbody tr {
        border-bottom: 1px solid rgba(0,0,0,0.03);
        transition: background 0.15s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(30,136,229,0.025); }
    td {
        padding: 16px 20px;
        font-size: 14px;
        vertical-align: middle;
    }

    .user-avatar {
        width: 40px; height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1e88e5, #42a5f5);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; color: #fff; font-weight: 700;
        flex-shrink: 0;
    }
    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-name-cell { font-weight: 700; color: var(--text); font-size: 14px; }
    .user-username  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    .badge-cabang {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        background: var(--blue-light);
        color: var(--blue);
    }
    .badge-cabang.merangkap {
        background: #fff8e1;
        color: #f59e0b;
        border: 1px solid rgba(245,158,11,0.25);
    }
    .badge-merangkap-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #fff8e1;
        color: #d97706;
        border: 1px solid rgba(245,158,11,0.2);
        margin-top: 3px;
    }
    .user-avatar.admin-avatar {
        background: linear-gradient(135deg, #7c3aed, #a78bfa);
    }
    tr.row-merangkap {
        background: rgba(245,158,11,0.03);
    }
    tr.row-merangkap:hover {
        background: rgba(245,158,11,0.06) !important;
    }
    .badge-cabang.unassigned {
        background: #f3f4f6;
        color: #9ca3af;
    }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-edit    { background: var(--blue-light);  color: var(--blue); }
    .btn-delete  { background: var(--red-light);   color: var(--red); }
    .btn-edit:hover   { background: var(--blue); color: #fff; }
    .btn-delete:hover { background: var(--red);  color: #fff; }

    .actions-cell { display: flex; gap: 6px; }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }
    .empty-icon { font-size: 48px; margin-bottom: 12px; }
    .empty-title { font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .empty-desc  { font-size: 14px; color: var(--text-muted); margin-bottom: 20px; }

    /* Modal konfirmasi */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(2,6,23,0.5);
        z-index: 9000;
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 24px 60px rgba(2,6,23,0.18);
        text-align: center;
        animation: modalIn 0.25s ease;
    }
    @keyframes modalIn { from { opacity:0; transform:scale(0.92) translateY(20px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .modal-icon { font-size: 44px; margin-bottom: 16px; }
    .modal-title { font-size: 18px; font-weight: 800; color: var(--text); margin-bottom: 8px; }
    .modal-desc  { font-size: 14px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
    .modal-actions { display: flex; gap: 10px; justify-content: center; }
    .modal-cancel {
        padding: 10px 24px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        font-size: 14px; font-weight: 700;
        cursor: pointer; color: var(--text);
        transition: all 0.15s;
    }
    .modal-cancel:hover { background: #f3f4f6; }
    .modal-confirm {
        padding: 10px 24px;
        border-radius: 10px;
        background: var(--red);
        color: #fff;
        font-size: 14px; font-weight: 700;
        border: none; cursor: pointer;
        transition: all 0.15s;
    }
    .modal-confirm:hover { background: #c62828; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<!-- Header -->
<div class="page-header">
    <div class="page-header-left">
        <h2>Manajemen Kepala Cabang</h2>
        <p>Kelola akun dan penempatan kepala cabang di setiap lokasi</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-tambah" id="btn-tambah-kepala">
        <i class="fas fa-user-plus"></i>
        Tambah Kepala Cabang
    </a>
</div>

<!-- Stats -->
@php
    $cabangMerangkap   = $cabang->where('admin_merangkap', true)->count();
    $totalTerisi       = $users->count() + $cabangMerangkap;
    $cabangBelumTerisi = $cabang->count() - $totalTerisi;
@endphp
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users-cog"></i></div>
        <div>
            <div class="stat-val">{{ $totalTerisi }}</div>
            <div class="stat-label">Cabang Terisi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-store"></i></div>
        <div>
            <div class="stat-val">{{ $cabang->count() }}</div>
            <div class="stat-label">Total Cabang</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-map-marker-alt"></i></div>
        <div>
            <div class="stat-val">{{ max(0, $cabangBelumTerisi) }}</div>
            <div class="stat-label">Cabang Belum Terisi</div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="table-card card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i class="fas fa-user-tie"></i>
            Daftar Kepala Cabang
        </div>
        <div class="cari-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari kepala cabang..." onkeyup="filterTable()">
        </div>
    </div>

    @if($users->isEmpty() && $cabang->where('admin_merangkap', true)->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">👤</div>
        <div class="empty-title">Belum ada kepala cabang</div>
        <div class="empty-desc">Tambahkan kepala cabang dan tugaskan ke cabang yang tersedia.</div>
        <a href="{{ route('users.create') }}" class="btn-tambah">
            <i class="fas fa-user-plus"></i> Tambah Sekarang
        </a>
    </div>
    @else
    <table id="userTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama / Akun</th>
                <th>Email</th>
                <th>Cabang Ditugaskan</th>
                <th>Lokasi Cabang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Baris kepala cabang biasa --}}
            @foreach($users as $i => $user)
            <tr>
                <td style="color:var(--text-muted); font-weight:600;">{{ $i + 1 }}</td>
                <td>
                    <div class="user-info-cell">
                        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <div class="user-name-cell">{{ $user->name }}</div>
                            <div class="user-username">{{ $user->username }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:var(--text-muted);">{{ $user->email }}</td>
                <td>
                    @if($user->cabang)
                        <span class="badge-cabang">
                            <i class="fas fa-store" style="font-size:10px;"></i>
                            {{ $user->cabang->nama_cabang }}
                        </span>
                    @else
                        <span class="badge-cabang unassigned">
                            <i class="fas fa-minus" style="font-size:10px;"></i>
                            Belum ditugaskan
                        </span>
                    @endif
                </td>
                <td style="color:var(--text-muted); font-size:13px;">
                    {{ $user->cabang?->lokasi ?? '-' }}
                </td>
                <td>
                    <div class="actions-cell">
                        <a href="{{ route('users.edit', $user) }}" class="btn-aksi btn-edit" id="btn-edit-{{ $user->id }}">
                            <i class="fas fa-pen"></i> Edit
                        </a>
                        <button class="btn-aksi btn-delete" id="btn-hapus-{{ $user->id }}"
                            onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach

            {{-- Baris khusus: Cabang admin merangkap --}}
            @foreach($cabang->where('admin_merangkap', true) as $cb)
            <tr class="row-merangkap">
                <td style="color:var(--text-muted); font-weight:600;">—</td>
                <td>
                    <div class="user-info-cell">
                        <div class="user-avatar admin-avatar">{{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}</div>
                        <div>
                            <div class="user-name-cell">{{ $adminUser?->name ?? 'Admin' }}</div>
                            <div class="user-username">{{ $adminUser?->username ?? '-' }}</div>
                            <div class="badge-merangkap-tag">
                                <i class="fas fa-shield-alt" style="font-size:9px;"></i>
                                Admin Merangkap
                            </div>
                        </div>
                    </div>
                </td>
                <td style="color:var(--text-muted);">{{ $adminUser?->email ?? '-' }}</td>
                <td>
                    <span class="badge-cabang merangkap">
                        <i class="fas fa-store" style="font-size:10px;"></i>
                        {{ $cb->nama_cabang }}
                    </span>
                </td>
                <td style="color:var(--text-muted); font-size:13px;">{{ $cb->lokasi ?? '-' }}</td>
                <td>
                    <span style="font-size:12px; color:var(--text-muted); font-style:italic;">Dikelola Admin</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<!-- Modal Hapus -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <div class="modal-title">Hapus Kepala Cabang?</div>
        <div class="modal-desc" id="deleteModalDesc">
            Apakah Anda yakin ingin menghapus kepala cabang ini? Tindakan ini tidak bisa dibatalkan.
        </div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-confirm">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows  = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(input) ? '' : 'none';
    });
}

function confirmDelete(id, name) {
    document.getElementById('deleteModalDesc').textContent =
        `Apakah Anda yakin ingin menghapus kepala cabang "${name}"? Tindakan ini tidak bisa dibatalkan.`;
    document.getElementById('deleteForm').action = `/users/${id}`;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
