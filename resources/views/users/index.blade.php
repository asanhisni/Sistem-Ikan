@extends('layouts.app')

@section('title', 'Manajemen Kepala Cabang')
@section('page-title', 'Manajemen Kepala Cabang')

@push('styles')
<style>
    .page-container {
        max-width: 900px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* ─── HEADER SECTION ─── */
    .kc-header {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 18px;
    }

    .kc-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kc-title-wrap h2 {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .kc-title-wrap p {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    .btn-tambah-kc {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.28);
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-tambah-kc:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(37, 99, 235, 0.38);
        color: #ffffff;
    }

    .btn-tambah-kc:active {
        transform: scale(0.98);
    }

    @media (max-width: 640px) {
        .btn-tambah-kc {
            width: 100%;
        }
    }

    /* ─── STATS CARDS (3 DALAM 1 BARIS SEJAJAR) ─── */
    .kc-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 22px;
    }

    .kc-stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 14px 8px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 8px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .kc-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
    }

    .kc-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .kc-stat-icon.blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .kc-stat-icon.green {
        background: #ecfdf5;
        color: #059669;
    }

    .kc-stat-icon.amber {
        background: #fffbeb;
        color: #d97706;
    }

    .kc-stat-val {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .kc-stat-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        line-height: 1.2;
    }

    @media (max-width: 480px) {
        .kc-stat-card {
            padding: 12px 6px;
            gap: 6px;
        }
        .kc-stat-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
            border-radius: 10px;
        }
        .kc-stat-val {
            font-size: 18px;
        }
        .kc-stat-label {
            font-size: 10px;
        }
    }

    /* ─── SECTION DAFTAR KEPALA CABANG ─── */
    .kc-list-section {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        padding: 20px 18px;
    }

    .kc-section-header {
        margin-bottom: 16px;
    }

    .kc-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .kc-section-title i {
        color: #2563eb;
        font-size: 17px;
    }

    /* ─── SEARCH BAR ─── */
    .kc-search-box {
        position: relative;
        width: 100%;
    }

    .kc-search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }

    .kc-search-input {
        width: 100%;
        padding: 12px 16px 12px 40px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        font-family: inherit;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .kc-search-input:focus {
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .kc-search-input::placeholder {
        color: #94a3b8;
    }

    /* ─── LIST ITEMS KEPALA CABANG ─── */
    .kc-items-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 14px;
    }

    .kc-user-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 14px 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.15s ease;
    }

    .kc-user-card:hover {
        border-color: #e2e8f0;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
    }

    .kc-user-card.merangkap-card {
        background: #fffdf7;
        border-color: #fef3c7;
    }

    .kc-user-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex: 1;
    }

    .kc-num-badge {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        min-width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    .kc-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.2);
    }

    .kc-avatar.admin-avatar {
        background: linear-gradient(135deg, #7c3aed, #8b5cf6);
        box-shadow: 0 3px 10px rgba(124, 58, 237, 0.2);
    }

    .kc-user-details {
        min-width: 0;
        flex: 1;
    }

    .kc-user-name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .kc-user-username {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-top: 1px;
    }

    .kc-user-email {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 5px;
        word-break: break-all;
    }

    .kc-user-email i {
        font-size: 11px;
        color: #94a3b8;
    }

    .kc-badges-wrap {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }

    .badge-cabang {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .badge-cabang.unassigned {
        background: #f8fafc;
        color: #94a3b8;
        border-color: #e2e8f0;
    }

    .badge-status-merangkap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .kc-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-kc-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-kc-action:active {
        transform: scale(0.94);
    }

    .btn-kc-edit {
        background: #f59e0b;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25);
    }

    .btn-kc-edit:hover {
        background: #d97706;
        color: #ffffff;
    }

    .btn-kc-delete {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
    }

    .btn-kc-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    @media (max-width: 640px) {
        .kc-user-card {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 14px;
        }
        .kc-actions {
            justify-content: flex-end;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
        }
    }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        padding: 40px 16px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 40px;
        color: #cbd5e1;
        margin-bottom: 10px;
    }

    .empty-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .empty-desc {
        font-size: 13px;
        color: #64748b;
    }

    /* ─── ALERTS ─── */
    .alert {
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* ─── MODAL HAPUS ─── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        z-index: 9999;
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        background: #ffffff;
        border-radius: 20px;
        padding: 26px 22px;
        max-width: 380px;
        width: 90%;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        text-align: center;
        animation: modalFadeIn 0.2s ease;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.94) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #fef2f2;
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .modal-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .modal-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .modal-btn-cancel {
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        color: #475569;
        flex: 1;
        transition: background 0.15s;
    }

    .modal-btn-cancel:hover {
        background: #f8fafc;
    }

    .modal-btn-confirm {
        padding: 10px 18px;
        border-radius: 12px;
        background: #ef4444;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        flex: 1;
        transition: background 0.15s;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }

    .modal-btn-confirm:hover {
        background: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="page-container">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 16px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle" style="font-size: 16px;"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Bagian Atas: Tombol Tambah --}}
    <div class="kc-header">
        <div class="kc-header-top" style="justify-content:flex-end;">
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:flex-end;">
                <button type="button" onclick="openModalCabang()" class="btn-tambah-kc" style="background:#ffffff; color:#2563eb; border:1.5px solid #bfdbfe; box-shadow:0 4px 14px rgba(37,99,235,0.08);">
                    <i class="fas fa-store"></i>
                    <span>Tambah Cabang Baru</span>
                </button>
                <a href="{{ route('users.create') }}" class="btn-tambah-kc" id="btn-tambah-kepala">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Kepala Cabang</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Bagian Statistik: 3 Kartu Dalam 1 Baris Sejajar --}}
    @php
        $cabangMerangkap   = $cabang->where('admin_merangkap', true)->count();
        $totalTerisi       = $users->count() + $cabangMerangkap;
        $cabangBelumTerisi = $cabang->count() - $totalTerisi;
    @endphp
    <div class="kc-stats-grid">
        <!-- 1. Cabang Terisi (Ikon Orang) -->
        <div class="kc-stat-card">
            <div class="kc-stat-icon blue">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="kc-stat-val">{{ $totalTerisi }}</div>
            <div class="kc-stat-label">Cabang Terisi</div>
        </div>

        <!-- 2. Total Cabang (Ikon Toko) -->
        <div class="kc-stat-card">
            <div class="kc-stat-icon green">
                <i class="fas fa-store"></i>
            </div>
            <div class="kc-stat-val">{{ $cabang->count() }}</div>
            <div class="kc-stat-label">Total Cabang</div>
        </div>

        <!-- 3. Cabang Belum Terisi (Ikon Lokasi) -->
        <div class="kc-stat-card">
            <div class="kc-stat-icon amber">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="kc-stat-val">{{ max(0, $cabangBelumTerisi) }}</div>
            <div class="kc-stat-label">Cabang Belum Terisi</div>
        </div>
    </div>

    {{-- Section: Daftar Kepala Cabang --}}
    <div class="kc-list-section">
        <div class="kc-section-header">
            <div class="kc-section-title">
                <i class="fas fa-user-tie"></i>
                <span>Daftar Kepala Cabang</span>
            </div>

            <!-- Kolom Pencarian (Search Bar) di Bawah Judul -->
            <div class="kc-search-box">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="kc-search-input" 
                    placeholder="Cari kepala cabang, username, atau email..." 
                    onkeyup="filterUsers()"
                >
            </div>
        </div>

        <!-- List Daftar Kepala Cabang -->
        <div class="kc-items-list" id="userListContainer">
            @php $index = 1; @endphp

            {{-- 1. Kepala Cabang Biasa --}}
            @foreach($users as $user)
            <div class="kc-user-card user-item-row" data-search="{{ strtolower($user->name . ' ' . $user->username . ' ' . $user->email . ' ' . ($user->cabang->nama_cabang ?? '')) }}">
                <div class="kc-user-left">
                    <div class="kc-num-badge">{{ $index++ }}</div>
                    <div class="kc-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="kc-user-details">
                        <div class="kc-user-name">
                            <span>{{ $user->name }}</span>
                        </div>
                        <div class="kc-user-username">{{ '@' . $user->username }}</div>
                        <div class="kc-user-email">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div class="kc-badges-wrap">
                            @if($user->cabang)
                                <span class="badge-cabang">
                                    <i class="fas fa-store" style="font-size: 10px;"></i>
                                    {{ $user->cabang->nama_cabang }}
                                </span>
                            @else
                                <span class="badge-cabang unassigned">
                                    <i class="fas fa-minus" style="font-size: 10px;"></i>
                                    Belum ditugaskan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="kc-actions">
                    <a href="{{ route('users.edit', $user) }}" class="btn-kc-action btn-kc-edit" id="btn-edit-{{ $user->id }}" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <button type="button" class="btn-kc-action btn-kc-delete" id="btn-hapus-{{ $user->id }}" title="Hapus" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach

            {{-- 2. Baris Khusus: Admin Merangkap --}}
            @foreach($cabang->where('admin_merangkap', true) as $cb)
            <div class="kc-user-card merangkap-card user-item-row" data-search="{{ strtolower(($adminUser?->name ?? 'Admin') . ' ' . ($adminUser?->username ?? '') . ' ' . ($adminUser?->email ?? '') . ' ' . $cb->nama_cabang . ' admin merangkap') }}">
                <div class="kc-user-left">
                    <div class="kc-num-badge">—</div>
                    <div class="kc-avatar admin-avatar">{{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}</div>
                    <div class="kc-user-details">
                        <div class="kc-user-name">
                            <span>{{ $adminUser?->name ?? 'Admin' }}</span>
                        </div>
                        <div class="kc-user-username">{{ '@' . ($adminUser?->username ?? 'admin') }}</div>
                        <div class="kc-user-email">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $adminUser?->email ?? '-' }}</span>
                        </div>
                        <div class="kc-badges-wrap">
                            <span class="badge-cabang">
                                <i class="fas fa-store" style="font-size: 10px;"></i>
                                {{ $cb->nama_cabang }}
                            </span>
                            <span class="badge-status-merangkap">
                                <i class="fas fa-shield-alt" style="font-size: 10px;"></i>
                                Admin Merangkap
                            </span>
                        </div>
                    </div>
                </div>

                <div class="kc-actions">
                    <span style="font-size: 12px; color: #94a3b8; font-style: italic; padding: 4px 8px;">
                        Dikelola Admin
                    </span>
                </div>
            </div>
            @endforeach

            {{-- Empty State jika tidak ada data sama sekali --}}
            @if($users->isEmpty() && $cabang->where('admin_merangkap', true)->isEmpty())
            <div class="empty-state">
                <i class="fas fa-user-friends"></i>
                <div class="empty-title">Belum Ada Kepala Cabang</div>
                <div class="empty-desc">Tambahkan kepala cabang baru untuk ditugaskan ke cabang toko.</div>
            </div>
            @endif

            {{-- Empty state hasil pencarian tidak ditemukan --}}
            <div id="noSearchMatch" class="empty-state" style="display: none;">
                <i class="fas fa-search"></i>
                <div class="empty-title">Tidak Ada Hasil Ditemukan</div>
                <div class="empty-desc">Coba kata kunci pencarian yang lain.</div>
            </div>
        </div>
    </div>

    {{-- Section: Daftar Semua Cabang & Aksi Hapus Cabang --}}
    <div class="kc-list-section" style="margin-top: 20px;">
        <div class="kc-section-header" style="margin-bottom: 6px;">
            <div class="kc-section-title" style="margin: 0;">
                <i class="fas fa-store"></i>
                <span>Daftar Cabang Aktif ({{ $cabang->count() }})</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; margin-top: 14px;">
            @foreach($cabang as $cb)
            @php
                $assignedUser = $users->firstWhere('cabang_id', $cb->id);
            @endphp
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:14px 16px; display:flex; flex-direction:column; justify-content:space-between; gap:10px;">
                <div>
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                        <span style="font-weight:800; color:#0f172a; font-size:15px;">{{ $cb->nama_cabang }}</span>
                        <span style="font-size:11px; font-weight:700; color:#64748b; background:#e2e8f0; padding:2px 6px; border-radius:5px;">
                            {{ $cb->kode_cabang ?: 'CBG' }}
                        </span>
                    </div>
                    <div style="font-size:12px; color:#64748b; margin-top:4px;">
                        <i class="fas fa-map-marker-alt" style="color:#94a3b8; font-size:11px;"></i>
                        <span>{{ $cb->lokasi ?: 'Lokasi belum diatur' }}</span>
                    </div>
                    <div style="margin-top:8px;">
                        @if($assignedUser)
                            <span style="font-size:11.5px; font-weight:700; color:#0369a1; background:#e0f2fe; padding:2px 8px; border-radius:6px; display:inline-flex; align-items:center; gap:4px;">
                                <i class="fas fa-user-check"></i> {{ $assignedUser->name }}
                            </span>
                        @else
                            <span style="font-size:11.5px; font-weight:600; color:#b45309; background:#fef3c7; padding:2px 8px; border-radius:6px; display:inline-flex; align-items:center; gap:4px;">
                                <i class="fas fa-clock"></i> Belum Ada Kepala
                            </span>
                        @endif
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end; border-top:1px solid #e2e8f0; padding-top:8px;">
                    <form action="{{ route('cabang.destroy', $cb->id) }}" method="POST" onsubmit="event.preventDefault(); confirmSwalDelete(this, 'Hapus Cabang {{ addslashes($cb->nama_cabang) }}?', 'Apakah Anda yakin ingin menghapus cabang ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; border-radius:8px; padding:6px 12px; font-size:12px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:all 0.15s ease;">
                            <i class="fas fa-trash-alt"></i> Hapus Cabang
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon-circle">
            <i class="fas fa-trash-alt"></i>
        </div>
        <div class="modal-title">Hapus Kepala Cabang?</div>
        <div class="modal-desc" id="deleteModalDesc">
            Apakah Anda yakin ingin menghapus kepala cabang ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        <div class="modal-actions">
            <button type="button" class="modal-btn-cancel" onclick="closeModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display: inline; flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm" style="width: 100%;">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>
<!-- ════ MODAL TAMBAH CABANG BARU ════ -->
<div id="modalTambahCabang" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(3px);">
    <div style="background:#fff; border-radius:20px; width:90%; max-width:440px; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.25); animation:modalSlideUp 0.2s ease;">
        <div style="padding:18px 24px; background:linear-gradient(135deg, #1e40af, #2563eb); color:#fff; display:flex; align-items:center; justify-content:space-between;">
            <div style="font-weight:800; font-size:16px; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-store"></i> Tambah Cabang Baru
            </div>
            <button type="button" onclick="closeModalCabang()" style="background:none; border:none; color:rgba(255,255,255,0.8); font-size:18px; cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('cabang.store') }}" method="POST" style="padding:22px;">
            @csrf
            <div class="form-group" style="margin-bottom:14px; text-align:left;">
                <label for="nama_cabang_modal" style="display:block; font-size:13px; font-weight:700; color:#1e293b; margin-bottom:6px;">Nama Cabang <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama_cabang" id="nama_cabang_modal" class="kc-search-input" placeholder="Contoh: Tarogong, Garut Kota, dll." required>
            </div>
            <div class="form-group" style="margin-bottom:14px; text-align:left;">
                <label for="lokasi_modal" style="display:block; font-size:13px; font-weight:700; color:#1e293b; margin-bottom:6px;">Lokasi / Alamat Cabang</label>
                <input type="text" name="lokasi" id="lokasi_modal" class="kc-search-input" placeholder="Contoh: Jl. Raya Tarogong No. 12">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" onclick="closeModalCabang()" style="background:#f1f5f9; color:#475569; border:none; padding:10px 16px; border-radius:10px; font-weight:700; font-size:13px; cursor:pointer;">Batal</button>
                <button type="submit" style="background:#2563eb; color:#fff; border:none; padding:10px 20px; border-radius:10px; font-weight:800; font-size:13px; cursor:pointer; box-shadow:0 4px 12px rgba(37,99,235,0.25);">Simpan Cabang</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModalCabang() {
    const modal = document.getElementById('modalTambahCabang');
    if (modal) modal.style.display = 'flex';
}

function closeModalCabang() {
    const modal = document.getElementById('modalTambahCabang');
    if (modal) modal.style.display = 'none';
}

function filterUsers() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.user-item-row');
    let visibleCount = 0;

    items.forEach(item => {
        const searchData = item.getAttribute('data-search') || '';
        if (searchData.includes(query)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const noMatch = document.getElementById('noSearchMatch');
    if (noMatch) {
        noMatch.style.display = (visibleCount === 0 && items.length > 0) ? 'block' : 'none';
    }
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

document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }
    const modalTambahCabang = document.getElementById('modalTambahCabang');
    if (modalTambahCabang) {
        modalTambahCabang.addEventListener('click', function(e) {
            if (e.target === this) closeModalCabang();
        });
    }
});
</script>
@endpush
