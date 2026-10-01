@extends('layouts.app')

@section('title', 'Ubah Transaksi #' . $transaksi->id)
@section('page-title', 'Ubah Data Transaksi & Cicilan')

@push('styles')
<style>
    .form-container {
        background: white;
        max-width: 740px;
        margin: 0 auto 40px;
        padding: 24px 20px;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        border: 1px solid #e8ecf0;
    }

    @media (max-width: 640px) {
        .form-container {
            padding: 18px 14px;
            border-radius: 14px;
        }
    }
    
    .form-group {
        margin-bottom: 24px;
        position: relative;
    }
    
    .form-group label.section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .form-group label.section-label i {
        color: #f59e0b;
    }
    
    .form-control {
        width: 100%;
        padding: 13px 15px;
        font-size: 15px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        outline: none;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
    }

    /* AUTOCOMPLETE SUGGESTION BOX */
    .autocomplete-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        z-index: 100;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        margin-top: 4px;
    }

    .autocomplete-item {
        padding: 10px 14px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.15s;
    }

    .autocomplete-item:last-child {
        border-bottom: none;
    }

    .autocomplete-item:hover {
        background: #fffbeb;
    }

    .ac-name {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .ac-phone {
        font-size: 12px;
        font-weight: 600;
        color: #d97706;
        background: #fef3c7;
        padding: 2px 8px;
        border-radius: 6px;
    }

    /* ─── UI PEMILIHAN IKAN CEPAT (GRID CHIPS) ─── */
    .ikan-picker-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
    }

    /* ─── HEADER GRUP TANGGAL DI DALAM PICKER ─── */
    .item-date-group-header {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1.5px solid #fde68a;
        border-radius: 10px;
        padding: 8px 13px;
        margin-top: 14px;
        margin-bottom: 6px;
    }

    .item-date-group-header i {
        color: #d97706;
        font-size: 13px;
    }

    .item-new-badge {
        margin-left: auto;
        font-size: 10.5px;
        font-weight: 700;
        color: #1d4ed8;
        background: #eff6ff;
        padding: 2px 8px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
    }

    .item-card-new {
        border-color: #bfdbfe !important;
        background: #f0f9ff !important;
    }

    /* ─── HEADER TANGGAL DI RIWAYAT ITEM ─── */
    .item-history-date-header {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1.5px solid #fde68a;
        border-radius: 10px;
        padding: 8px 13px;
        margin-top: 10px;
        margin-bottom: 6px;
    }

    .item-history-date-header i {
        color: #d97706;
    }

    .ikan-chips-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .chip-ikan-btn {
        background: white;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 68px;
    }

    .chip-ikan-btn:hover {
        border-color: #f59e0b;
        background: #fffbeb;
        transform: translateY(-2px);
    }

    .chip-ikan-btn:active {
        transform: scale(0.97);
    }

    .chip-ikan-nama {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }

    .chip-ikan-harga {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        margin-top: 4px;
    }

    /* DAFTAR ITEM YANG SUDAH DIPILIH */
    .items-list-wrapper {
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .item-card {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }

    .item-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .item-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .item-card-subtotal {
        font-size: 15px;
        font-weight: 800;
        color: #d97706;
    }

    .item-card-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .qty-counter {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 6px;
        border-radius: 10px;
    }

    .qty-counter-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: white;
        color: #0f172a;
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        user-select: none;
    }

    .qty-counter-btn:active {
        background: #e2e8f0;
    }

    .qty-counter-input {
        width: 65px;
        text-align: center;
        font-size: 15px;
        font-weight: 800;
        border: 1px solid #cbd5e1;
        background: white;
        border-radius: 8px;
        padding: 5px 4px;
        color: #0f172a;
        outline: none;
    }

    .qty-unit {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
    }

    .btn-item-delete {
        background: #fee2e2;
        color: #ef4444;
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.15s;
    }

    .btn-item-delete:hover {
        background: #ef4444;
        color: white;
    }

    .empty-items-placeholder {
        text-align: center;
        padding: 20px 10px;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 500;
        background: white;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .ringkasan-banner {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-radius: 14px;
        padding: 14px 18px;
        margin-top: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.25);
    }

    .ringkasan-banner-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.9;
    }

    .ringkasan-banner-items {
        font-size: 14px;
        font-weight: 800;
        margin-top: 2px;
    }

    .ringkasan-banner-total {
        font-size: 20px;
        font-weight: 900;
        letter-spacing: -0.02em;
    }

    /* ─── RIWAYAT ITEM IKAN (mirip cicilan) ─── */
    .item-history-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px 18px;
        margin-bottom: 18px;
        box-shadow: 0 2px 10px rgba(15,23,42,0.03);
    }

    .item-history-title {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .item-history-count-badge {
        font-size: 11.5px;
        font-weight: 700;
        color: #d97706;
        background: #fffbeb;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid #fde68a;
    }

    .item-history-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .item-history-row {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 11px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 1px 4px rgba(15,23,42,0.02);
        transition: box-shadow 0.15s;
    }

    .item-history-row:hover {
        box-shadow: 0 3px 10px rgba(15,23,42,0.06);
    }

    .item-history-left {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .item-history-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #fffbeb;
        color: #b45309;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        border: 1.5px solid #fde68a;
    }

    .item-history-nama {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 160px;
    }

    .item-history-meta {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .item-history-right {
        text-align: right;
        flex-shrink: 0;
    }

    .item-history-subtotal {
        font-size: 14px;
        font-weight: 800;
        color: #b45309;
        background: #fffbeb;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #fde68a;
        white-space: nowrap;
    }

    .item-history-kg {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 700;
        margin-top: 3px;
        text-align: right;
    }

    /* ─── 4. MULTI-PAYMENT & CICILAN SECTION ─── */
    .payment-section-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px 20px;
        margin-top: 6px;
    }

    @media (max-width: 640px) {
        .payment-section-box {
            padding: 16px 14px;
        }
    }

    /* 1. RINGKASAN FINANSIAL (4 Kolom) */
    .payment-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 26px;
    }

    @media (max-width: 768px) {
        .payment-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    .stat-card-item {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
        transition: all 0.15s ease;
    }

    .stat-card-lbl {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .stat-card-val {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
    }

    .stat-card-item.paid {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .stat-card-item.paid .stat-card-lbl { color: #166534; }
    .stat-card-item.paid .stat-card-val { color: #15803d; }

    .stat-card-item.danger {
        background: #fef2f2;
        border-color: #fecaca;
    }
    .stat-card-item.danger .stat-card-lbl { color: #991b1b; }
    .stat-card-item.danger .stat-card-val { color: #dc2626; }

    .stat-card-item.success {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .stat-card-item.success .stat-card-lbl { color: #166534; }
    .stat-card-item.success .stat-card-val { color: #15803d; }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.02em;
        width: fit-content;
    }

    .badge-status-pill.lunas {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }

    .badge-status-pill.dp {
        background: #fef9c3;
        color: #a16207;
        border: 1px solid #fde047;
    }

    .badge-status-pill.belum {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    /* 2. RIWAYAT PEMBAYARAN LIST */
    .cicilan-list-title {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cicilan-count-badge {
        font-size: 11.5px;
        font-weight: 700;
        color: #2563eb;
        background: #eff6ff;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
    }

    .cicilan-history-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 26px;
    }

    .cicilan-row-item {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.02);
    }

    .cicilan-row-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cicilan-row-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .cicilan-row-date {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .cicilan-row-note {
        font-size: 12px;
        color: #64748b;
    }

    .cicilan-row-nominal {
        font-size: 15px;
        font-weight: 800;
        color: #15803d;
        background: #f0fdf4;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #bbf7d0;
        white-space: nowrap;
    }

    .empty-cicilan-box {
        text-align: center;
        padding: 20px 14px;
        background: #ffffff;
        border-radius: 14px;
        border: 1.5px dashed #cbd5e1;
    }

    /* 3. BOX TAMBAH CICILAN BARU */
    .new-cicilan-box {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 16px;
        padding: 20px 18px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .new-cicilan-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .new-cicilan-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .new-cicilan-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .new-cicilan-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .new-cicilan-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 640px) {
        .new-cicilan-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    .btn-simpan {
        width: 100%;
        background: linear-gradient(135deg, #1e88e5, #1565c0);
        color: white;
        padding: 16px;
        border: none;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(30, 136, 229, 0.3);
        margin-top: 20px;
        transition: transform 0.15s, box-shadow 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-simpan:hover {
        background: #1565c0;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30, 136, 229, 0.4);
    }
    
    .btn-batal {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-align: center;
        width: 100%;
        padding: 14px;
        margin-top: 10px;
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        background: #f1f5f9;
        border-radius: 12px;
        box-sizing: border-box;
    }

    .btn-batal:hover {
        background: #e2e8f0;
        color: #334155;
    }

    /* Modal Prompt Volume */
    .prompt-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .prompt-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .prompt-modal-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        max-width: 380px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .prompt-modal-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .prompt-modal-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .prompt-modal-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .prompt-modal-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .prompt-modal-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-prompt-submit {
        background: #f59e0b;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 13px 18px;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-prompt-cancel {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: 13px 18px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<div class="form-container">
    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksiForm" onsubmit="return validateItems()">
        @csrf
        @method('PUT')
        
        <!-- 1. No HP / WhatsApp -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-phone-alt"></i>
                <span>1. No. HP / WhatsApp Pembeli</span>
            </label>
            <input type="text" name="telepon" id="teleponInput" class="form-control" 
                   placeholder="Contoh: 081234567890 (Ketik no hp untuk autocomplete)" 
                   value="{{ old('telepon', $transaksi->telepon ?? ($transaksi->pelanggan?->telepon ?? '')) }}" 
                   autocomplete="off" oninput="searchPelangganByPhone(this.value)">
            
            <!-- Box Rekomendasi Pelanggan Terdaftar -->
            <div id="phoneAutocompleteBox" class="autocomplete-box"></div>
            
            <div id="pelangganFoundNotice" style="display: none; margin-top: 6px; font-size: 12px; font-weight: 700; color: #15803d;">
                <i class="fas fa-check-circle"></i> Pelanggan terdaftar ditemukan!
            </div>
        </div>

        <!-- 2. Nama Pembeli -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-user"></i>
                <span>2. Nama Pembeli / Tempat</span>
            </label>
            <input type="text" name="pembeli_nama" id="pembeliNamaInput" class="form-control" 
                   value="{{ old('pembeli_nama', $transaksi->pembeli_nama) }}" 
                   required autocomplete="off" oninput="searchPelangganByName(this.value)">
            
            <!-- Box Rekomendasi Pelanggan berdasarkan Nama -->
            <div id="nameAutocompleteBox" class="autocomplete-box"></div>

            @error('pembeli_nama') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        
        <!-- 3. Multi Jenis Ikan & Volume -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-fish"></i>
                <span>3. Pilih Ikan & Masukkan Kilogram</span>
            </label>

            <div class="ikan-picker-card">
                <!-- Pilihan Cepat (Tinggal Klik) -->
                <div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">
                    Sentuh jenis ikan untuk tambah:
                </div>
                
                <div class="ikan-chips-grid">
                    @foreach($ikan as $i)
                        <div class="chip-ikan-btn" onclick="openVolumePrompt({{ $i->id }}, '{{ addslashes($i->nama_ikan) }}', {{ $i->harga_per_kg }})">
                            <span class="chip-ikan-nama">{{ $i->nama_ikan }}</span>
                            <span class="chip-ikan-harga">Rp {{ number_format($i->harga_per_kg, 0, ',', '.') }}/Kg</span>
                        </div>
                    @endforeach
                </div>

                <!-- Daftar Ikan yang Masuk Pesanan -->
                <div style="font-size: 13px; font-weight: 800; color: #1e293b; margin-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                    <span>Ikan yang dipesan:</span>
                    <span id="labelItemCount" style="font-size: 12px; color: #64748b; font-weight: 600;">(0 jenis)</span>
                </div>

                <div id="itemsContainer" class="items-list-wrapper">
                    <!-- Diisi lewat Javascript -->
                </div>

                <div id="emptyIkanPlaceholder" class="empty-items-placeholder">
                    <i class="fas fa-hand-pointer" style="margin-right: 6px;"></i> Belum ada ikan dipilih. Klik tombol ikan di atas.
                </div>

                <!-- Total Banner -->
                <div id="ringkasanBanner" class="ringkasan-banner" style="display: none;">
                    <div>
                        <div class="ringkasan-banner-label">Total Belanja</div>
                        <div class="ringkasan-banner-items" id="bannerTotalKg">0 Kg</div>
                    </div>
                    <div class="ringkasan-banner-total" id="bannerTotalHarga">Rp 0</div>
                </div>
            </div>
            
            <div id="itemsErrorMsg" class="error-msg" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i> Minimal pilih 1 jenis ikan untuk melanjutkan.
            </div>
            @error('items') <div class="error-msg">{{ $message }}</div> @enderror
        <!-- 4. Multi Payment & Cicilan Section -->
        <div class="form-group" style="margin-top: 28px;">
            <label class="section-label">
                <i class="fas fa-money-bill-wave"></i>
                <span>4. Riwayat Pembayaran &amp; Tambah Cicilan</span>
            </label>
            
            <div class="payment-section-box">
                @php
                    $totalSudahBayar = $transaksi->total_dibayar;
                    $currentSisa = $transaksi->sisa_tagihan;
                @endphp

                <!-- 1. Ringkasan Finansial 4 Kolom di Bagian Atas -->
                <div class="payment-stats-grid">
                    <div class="stat-card-item">
                        <div class="stat-card-lbl"><i class="fas fa-receipt"></i> Total Belanja</div>
                        <div class="stat-card-val" id="statLiveTotal">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-card-item paid">
                        <div class="stat-card-lbl"><i class="fas fa-hand-holding-usd"></i> Sudah Dibayar</div>
                        <div class="stat-card-val" id="statSudahDibayar">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-card-item {{ $currentSisa > 0 ? 'danger' : 'success' }}" id="statSisaBox">
                        <div class="stat-card-lbl"><i class="fas fa-coins"></i> Sisa Tagihan</div>
                        <div class="stat-card-val" id="statLiveSisa">
                            Rp {{ number_format($currentSisa, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="stat-card-item status" id="statStatusBox">
                        <div class="stat-card-lbl"><i class="fas fa-info-circle"></i> Status</div>
                        <div class="stat-card-val status-val" id="statLiveStatus">
                            @if($currentSisa <= 0 && $totalSudahBayar > 0)
                                <span class="badge-status-pill lunas"><i class="fas fa-check-circle"></i> LUNAS</span>
                            @elseif($totalSudahBayar > 0)
                                <span class="badge-status-pill dp"><i class="fas fa-clock"></i> BAYAR DP</span>
                            @else
                                <span class="badge-status-pill belum"><i class="fas fa-times-circle"></i> BELUM BAYAR</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 2. Riwayat Pembayaran Tercatat -->
                <div class="cicilan-section-wrapper">
                    <div class="cicilan-list-title">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-history" style="color: #2563eb; font-size: 15px;"></i>
                            <span>Riwayat Pembayaran</span>
                        </div>
                        <span class="cicilan-count-badge">{{ $transaksi->pembayarans->count() }}x Pembayaran</span>
                    </div>

                    <div class="cicilan-history-container">
                        @forelse($transaksi->pembayarans as $idx => $p)
                            <div class="cicilan-row-item">
                                <div class="cicilan-row-left">
                                    <div class="cicilan-row-badge">#{{ $idx + 1 }}</div>
                                    <div>
                                        <div class="cicilan-row-date">
                                            <i class="far fa-calendar-alt" style="color: #64748b; margin-right: 4px;"></i>
                                            {{ $p->tanggal_bayar->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                        </div>
                                        @if($p->keterangan && $p->keterangan !== 'Pembayaran Cicilan')
                                            <div class="cicilan-row-note">
                                                <i class="fas fa-comment-alt" style="color: #94a3b8; margin-right: 4px; font-size: 10px;"></i>
                                                {{ $p->keterangan }}
                                                @if($p->user)
                                                    <span style="color: #94a3b8; margin-left: 4px;">(Oleh: {{ $p->user->name }})</span>
                                                @endif
                                            </div>
                                        @elseif($p->user)
                                            <div class="cicilan-row-note" style="color: #94a3b8;">
                                                Oleh: {{ $p->user->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="cicilan-row-nominal">
                                    +Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-cicilan-box">
                                <i class="fas fa-file-invoice-dollar" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"></i>
                                <div style="font-weight: 700; color: #64748b; font-size: 13px;">Belum ada riwayat pembayaran yang dicatat</div>
                                <div style="font-size: 11.5px; color: #94a3b8; margin-top: 2px;">Gunakan form di bawah untuk mencatat cicilan pembayaran baru</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 3. Form Tambah Cicilan Pembayaran Baru -->
                <div class="new-cicilan-box">
                    <div class="new-cicilan-header">
                        <div class="new-cicilan-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>
                            <div class="new-cicilan-title">Tambah Cicilan Pembayaran Baru</div>
                            <div class="new-cicilan-subtitle">Isi nominal di bawah jika pelanggan melakukan pembayaran</div>
                        </div>
                    </div>

                    <!-- Nominal Cicilan Baru -->
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 6px; display: block;">
                            Nominal Cicilan Baru (Rp)
                        </label>
                        <div style="position: relative;">
                            <input type="text" id="displayNewPaymentNominal" class="form-control" 
                                   placeholder="Contoh: 50.000 atau 200.000" oninput="formatRupiahNewPayment(this)"
                                   style="font-size: 16px; font-weight: 800; color: #15803d; letter-spacing: 0.02em;">
                            <input type="hidden" name="new_payment_nominal" id="inputNewPaymentNominal" value="">
                        </div>
                    </div>

                    <!-- Grid Tanggal & Catatan -->
                    <div class="new-cicilan-grid">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 5px; display: block;">
                                Tanggal Bayar
                            </label>
                            <input type="date" name="new_payment_tanggal" class="form-control" value="{{ now()->format('Y-m-d') }}" style="font-size: 13.5px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 5px; display: block;">
                                Catatan (Opsional)
                            </label>
                            <input type="text" name="new_payment_keterangan" class="form-control" placeholder="Contoh: Transfer BCA / Titip Kasir" style="font-size: 13.5px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 5. Keterangan Umum Transaksi -->
        <div class="form-group" style="margin-top: 24px;">
            <label class="section-label">
                <i class="fas fa-pencil-alt"></i>
                <span>5. Catatan Tambahan Transaksi (Opsional)</span>
            </label>
            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Diambil sore hari" value="{{ old('keterangan', $transaksi->keterangan) }}">
        </div>
        
        <button type="submit" class="btn-simpan">
            <i class="fas fa-save"></i> SIMPAN PERUBAHAN &amp; CICILAN
        </button>
        <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn-batal">
            <i class="fas fa-arrow-left"></i> BATAL / KEMBALI KE STRUK
        </a>
    </form>
</div>

<!-- Modal Prompt Volume (Kg) & Harga Satuan -->
<div class="prompt-modal-overlay" id="promptVolumeModal">
    <div class="prompt-modal-card">
        <div class="prompt-modal-header">
            <div class="prompt-modal-icon">
                <i class="fas fa-fish"></i>
            </div>
            <div>
                <div class="prompt-modal-title" id="promptNamaIkan">Nama Ikan</div>
                <div class="prompt-modal-subtitle" id="promptHargaIkanNormal">Harga Normal: Rp 0 / Kg</div>
            </div>
        </div>
        
        <!-- Input 1: Harga per Kg (Bisa Diketik Manual untuk Nawar) -->
        <div class="form-group" style="margin-bottom: 14px;">
            <label style="font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 5px; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-tag" style="color: #f59e0b; margin-right: 4px;"></i> Harga per Kg (Rp)</span>
                <span id="btnResetHargaNormal" style="font-size: 11px; color: #d97706; font-weight: 700; cursor: pointer; text-decoration: underline;" onclick="resetToNormalPrice()">Pakai Harga Normal</span>
            </label>
            <input type="text" id="promptDisplayHarga" class="form-control" placeholder="Contoh: 35.000" oninput="formatRupiahPromptHarga(this)" style="font-size: 16px; font-weight: 800; color: #d97706;">
            <input type="hidden" id="promptRealHarga" value="">
        </div>

        <!-- Input 2: Jumlah Kg -->
        <div class="form-group" style="margin-bottom: 12px;">
            <label style="font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 5px; display: block;">
                <i class="fas fa-weight-hanging" style="color: #f59e0b; margin-right: 4px;"></i> Jumlah Kilogram (Kg)
            </label>
            <input type="number" step="0.1" min="0.1" id="promptJumlahKg" class="form-control" placeholder="Contoh: 10 atau 12.5" oninput="updatePromptSubtotalPreview()" onkeydown="if(event.key==='Enter'){ event.preventDefault(); confirmAddIkan(); }">
            <div id="promptErrorMsg" class="error-msg" style="display: none;">Masukkan angka Kg yang valid (min. 0.1 Kg).</div>
        </div>

        <!-- Live Estimasi Subtotal -->
        <div id="promptSubtotalBox" style="background: #fffbeb; border: 1.5px dashed #fcd34d; border-radius: 12px; padding: 10px 14px; margin-bottom: 6px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px; font-weight: 700; color: #92400e;">Estimasi Subtotal:</span>
            <span id="promptSubtotalText" style="font-size: 15px; font-weight: 800; color: #b45309;">Rp 0</span>
        </div>

        <div class="prompt-modal-actions">
            <button type="button" class="btn-prompt-cancel" onclick="closePromptModal()">Batal</button>
            <button type="button" class="btn-prompt-submit" onclick="confirmAddIkan()">
                <i class="fas fa-plus"></i> Tambah Item
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const masterPelanggan = @json($pelanggan ?? []);
    const existingPaidTotal = {{ (float) $totalSudahBayar }};

    function searchPelangganByPhone(query) {
        const box = document.getElementById('phoneAutocompleteBox');
        const notice = document.getElementById('pelangganFoundNotice');
        
        if (!query || query.length < 3) {
            box.style.display = 'none';
            notice.style.display = 'none';
            return;
        }

        const cleanQuery = query.toLowerCase().replace(/[^0-9]/g, '');
        const matches = masterPelanggan.filter(p => {
            const cleanPhone = (p.telepon || '').replace(/[^0-9]/g, '');
            return cleanPhone.includes(cleanQuery);
        });

        if (matches.length === 0) {
            box.style.display = 'none';
            notice.style.display = 'none';
            return;
        }

        let html = '';
        matches.forEach(p => {
            html += `
                <div class="autocomplete-item" onclick="selectPelanggan('${p.nama.replace(/'/g, "\\'")}', '${(p.telepon || '').replace(/'/g, "\\'")}')">
                    <span class="ac-name">${p.nama}</span>
                    <span class="ac-phone"><i class="fas fa-phone"></i> ${p.telepon}</span>
                </div>
            `;
        });

        box.innerHTML = html;
        box.style.display = 'block';
    }

    function searchPelangganByName(query) {
        const box = document.getElementById('nameAutocompleteBox');
        
        if (!query || query.length < 2) {
            box.style.display = 'none';
            return;
        }

        const cleanQuery = query.toLowerCase();
        const matches = masterPelanggan.filter(p => p.nama.toLowerCase().includes(cleanQuery));

        if (matches.length === 0) {
            box.style.display = 'none';
            return;
        }

        let html = '';
        matches.forEach(p => {
            html += `
                <div class="autocomplete-item" onclick="selectPelanggan('${p.nama.replace(/'/g, "\\'")}', '${(p.telepon || '').replace(/'/g, "\\'")}')">
                    <span class="ac-name">${p.nama}</span>
                    ${p.telepon ? `<span class="ac-phone"><i class="fas fa-phone"></i> ${p.telepon}</span>` : ''}
                </div>
            `;
        });

        box.innerHTML = html;
        box.style.display = 'block';
    }

    function selectPelanggan(nama, telepon) {
        document.getElementById('pembeliNamaInput').value = nama;
        if (telepon) {
            document.getElementById('teleponInput').value = telepon;
        }
        document.getElementById('phoneAutocompleteBox').style.display = 'none';
        document.getElementById('nameAutocompleteBox').style.display = 'none';
        document.getElementById('pelangganFoundNotice').style.display = 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#teleponInput') && !e.target.closest('#phoneAutocompleteBox')) {
            document.getElementById('phoneAutocompleteBox').style.display = 'none';
        }
        if (!e.target.closest('#pembeliNamaInput') && !e.target.closest('#nameAutocompleteBox')) {
            document.getElementById('nameAutocompleteBox').style.display = 'none';
        }
    });

    let selectedItems = [];
    let pendingIkan = null;

    @if($transaksi->details && $transaksi->details->count() > 0)
        @foreach($transaksi->details as $d)
            @php
                $itemDate = $d->created_at ? $d->created_at->format('Y-m-d') : ($transaksi->tanggal_transaksi ? $transaksi->tanggal_transaksi->format('Y-m-d') : now()->format('Y-m-d'));
            @endphp
            selectedItems.push({
                id: {{ $d->ikan_id }},
                nama: "{{ $d->ikan ? addslashes($d->ikan->nama_ikan) : 'Ikan' }}",
                harga: {{ (float) $d->harga_per_kg }},
                hargaNormal: {{ $d->ikan ? (float)$d->ikan->harga_per_kg : (float)$d->harga_per_kg }},
                kg: {{ (float) $d->jumlah_kg }},
                detailId: {{ $d->id }},
                tanggal: "{{ $itemDate }}"
            });
        @endforeach
    @elseif($transaksi->ikan)
        @php
            $trxDate = $transaksi->tanggal_transaksi ? $transaksi->tanggal_transaksi->format('Y-m-d') : now()->format('Y-m-d');
        @endphp
        selectedItems.push({
            id: {{ $transaksi->ikan_id }},
            nama: "{{ addslashes($transaksi->ikan->nama_ikan) }}",
            harga: {{ (float) $transaksi->harga_per_kg }},
            hargaNormal: {{ (float) $transaksi->ikan->harga_per_kg }},
            kg: {{ (float) $transaksi->jumlah_kg }},
            detailId: null,
            tanggal: "{{ $trxDate }}"
        });
    @endif

    function openVolumePrompt(id, nama, hargaNormal) {
        pendingIkan = { 
            id: id, 
            nama: nama, 
            hargaNormal: parseFloat(hargaNormal) || 0,
            hargaCustom: parseFloat(hargaNormal) || 0
        };

        document.getElementById('promptNamaIkan').textContent = pendingIkan.nama;
        document.getElementById('promptHargaIkanNormal').textContent = 'Harga Normal: Rp ' + Number(pendingIkan.hargaNormal).toLocaleString('id-ID') + ' / Kg';
        
        // Set default harga ke harga normal
        document.getElementById('promptRealHarga').value = pendingIkan.hargaNormal;
        document.getElementById('promptDisplayHarga').value = Number(pendingIkan.hargaNormal).toLocaleString('id-ID');

        document.getElementById('promptJumlahKg').value = '';
        document.getElementById('promptErrorMsg').style.display = 'none';
        updatePromptSubtotalPreview();
        
        const modal = document.getElementById('promptVolumeModal');
        modal.classList.add('show');
        setTimeout(() => {
            document.getElementById('promptJumlahKg').focus();
        }, 100);
    }

    function resetToNormalPrice() {
        if (!pendingIkan) return;
        document.getElementById('promptRealHarga').value = pendingIkan.hargaNormal;
        document.getElementById('promptDisplayHarga').value = Number(pendingIkan.hargaNormal).toLocaleString('id-ID');
        updatePromptSubtotalPreview();
    }

    function formatRupiahPromptHarga(inputEl) {
        let val = inputEl.value.replace(/\D/g, '');
        let realInput = document.getElementById('promptRealHarga');
        if (!val) {
            realInput.value = '0';
            inputEl.value = '';
        } else {
            realInput.value = val;
            inputEl.value = Number(val).toLocaleString('id-ID');
        }
        updatePromptSubtotalPreview();
    }

    function updatePromptSubtotalPreview() {
        const harga = parseFloat(document.getElementById('promptRealHarga').value) || 0;
        const kg = parseFloat(document.getElementById('promptJumlahKg').value) || 0;
        const subtotal = harga * kg;
        document.getElementById('promptSubtotalText').textContent = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
    }

    function closePromptModal() {
        document.getElementById('promptVolumeModal').classList.remove('show');
        pendingIkan = null;
    }

    function confirmAddIkan() {
        const kgInput = document.getElementById('promptJumlahKg');
        const kg = parseFloat(kgInput.value);
        const hargaVal = parseFloat(document.getElementById('promptRealHarga').value);

        if (!kg || kg <= 0) {
            document.getElementById('promptErrorMsg').style.display = 'block';
            kgInput.focus();
            return;
        }

        const hargaPerKg = (!isNaN(hargaVal) && hargaVal >= 0) ? hargaVal : pendingIkan.hargaNormal;

        // Cek apakah item dengan ID, HARGA, dan TANGGAL yang sama (hari ini) sudah ada
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;

        const existingIndex = selectedItems.findIndex(i =>
            String(i.id) === String(pendingIkan.id) &&
            Number(i.harga) === Number(hargaPerKg) &&
            !i.detailId && // hanya gabung jika item baru (belum tersimpan)
            i.tanggal === todayStr
        );
        if (existingIndex > -1) {
            selectedItems[existingIndex].kg = parseFloat((selectedItems[existingIndex].kg + kg).toFixed(2));
        } else {
            selectedItems.push({
                id: pendingIkan.id,
                nama: pendingIkan.nama,
                harga: hargaPerKg,
                hargaNormal: pendingIkan.hargaNormal,
                kg: kg,
                detailId: null,
                tanggal: todayStr
            });
        }

        closePromptModal();
        renderItems();
    }

    function removeIkanItem(index) {
        selectedItems.splice(index, 1);
        renderItems();
    }

    function stepItemKg(index, delta) {
        let current = parseFloat(selectedItems[index].kg) || 0;
        let next = Math.max(0.1, parseFloat((current + delta).toFixed(1)));
        selectedItems[index].kg = next;
        renderItems();
    }

    function updateItemKg(index, val) {
        let parsed = parseFloat(val);
        if (parsed && parsed > 0) {
            selectedItems[index].kg = parsed;
            updateTotalsOnly();
        }
    }

    function renderItems() {
        const container = document.getElementById('itemsContainer');
        const emptyPlaceholder = document.getElementById('emptyIkanPlaceholder');
        const banner = document.getElementById('ringkasanBanner');
        const labelCount = document.getElementById('labelItemCount');
        const errorMsg = document.getElementById('itemsErrorMsg');

        if (selectedItems.length === 0) {
            container.innerHTML = '';
            emptyPlaceholder.style.display = 'block';
            banner.style.display = 'none';
            labelCount.textContent = '(0 jenis)';
            updateTotalsOnly();
            return;
        }

        emptyPlaceholder.style.display = 'none';
        banner.style.display = 'flex';
        errorMsg.style.display = 'none';
        labelCount.textContent = `(${selectedItems.length} jenis)`;

        // ── Kelompokkan item berdasarkan tanggal ──
        const groups = {};
        const now = new Date();
        const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

        selectedItems.forEach((item, idx) => {
            const tgl = item.tanggal || todayStr;
            if (!groups[tgl]) groups[tgl] = [];
            groups[tgl].push({ item, idx });
        });

        // ── Helper: format tanggal Indonesia ──
        function formatTanggalID(dateStr) {
            if (!dateStr) return 'Hari Ini';
            const d = new Date(dateStr + 'T00:00:00');
            const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][d.getDay()];
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][d.getMonth()];
            return `${hari}, ${d.getDate()} ${bulan} ${d.getFullYear()}`;
        }

        // ── Urutkan tanggal ascending ──
        const sortedDates = Object.keys(groups).sort();

        let html = '';
        sortedDates.forEach(tgl => {
            const isToday = (tgl === todayStr);
            const groupItems = groups[tgl];

            // Header tanggal
            html += `
                <div class="item-date-group-header">
                    <i class="far fa-calendar-alt"></i>
                    ${formatTanggalID(tgl)}
                    ${isToday && groupItems.some(g => !g.item.detailId) ? '<span class="item-new-badge">Tambahan Baru</span>' : ''}
                </div>
            `;

            groupItems.forEach(({ item, idx }) => {
                const subtotal = item.harga * item.kg;
                const isCustomPrice = item.hargaNormal && Number(item.harga) !== Number(item.hargaNormal);
                const isNewItem = !item.detailId;

                html += `
                    <div class="item-card ${isNewItem ? 'item-card-new' : ''}">
                        <div class="item-card-top">
                            <div>
                                <div class="item-card-title">${item.nama}</div>
                                <div style="font-size: 12.5px; color: #d97706; font-weight: 700; display: flex; align-items: center; gap: 5px; margin-top: 2px; flex-wrap: wrap;">
                                    <span>${Number(item.kg).toLocaleString('id-ID', {minimumFractionDigits:0})} kg × Rp ${Number(item.harga).toLocaleString('id-ID')}</span>
                                    ${isCustomPrice ? '<span style="font-size:10px;background:#fef3c7;color:#b45309;padding:1px 5px;border-radius:4px;font-weight:700;">(Harga Khusus)</span>' : ''}
                                </div>
                            </div>
                            <div class="item-card-subtotal" id="subtotal-${idx}">
                                Rp ${Number(subtotal).toLocaleString('id-ID')}
                            </div>
                        </div>
                        <div class="item-card-bottom">
                            <div class="qty-counter">
                                <button type="button" class="qty-counter-btn" onclick="stepItemKg(${idx}, -1)">-</button>
                                <input type="number" step="0.1" min="0.1" name="items[${idx}][jumlah_kg]" class="qty-counter-input"
                                       value="${item.kg}" onchange="updateItemKg(${idx}, this.value)">
                                <button type="button" class="qty-counter-btn" onclick="stepItemKg(${idx}, 1)">+</button>
                                <span class="qty-unit">Kg</span>
                            </div>
                            <input type="hidden" name="items[${idx}][ikan_id]" value="${item.id}">
                            <input type="hidden" name="items[${idx}][harga_per_kg]" value="${item.harga}">
                            <input type="hidden" name="items[${idx}][tanggal]" value="${item.tanggal || todayStr}">
                            ${item.detailId ? `<input type="hidden" name="items[${idx}][detail_id]" value="${item.detailId}">` : ''}
                            <button type="button" class="btn-item-delete" onclick="removeIkanItem(${idx})" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        });

        container.innerHTML = html;
        updateTotalsOnly();
    }

    function calculateCurrentTotal() {
        let totalHarga = 0;
        selectedItems.forEach(item => {
            totalHarga += (item.harga * item.kg);
        });
        return totalHarga;
    }

    function updateTotalsOnly() {
        let totalKg = 0;
        let totalHarga = 0;

        selectedItems.forEach((item, idx) => {
            const subtotal = item.harga * item.kg;
            totalKg += item.kg;
            totalHarga += subtotal;
            const subEl = document.getElementById(`subtotal-${idx}`);
            if (subEl) {
                subEl.textContent = `Rp ${Number(subtotal).toLocaleString('id-ID')}`;
            }
        });

        document.getElementById('bannerTotalKg').textContent = `${totalKg.toFixed(1)} Kg (${selectedItems.length} jenis)`;
        document.getElementById('bannerTotalHarga').textContent = `Rp ${Number(totalHarga).toLocaleString('id-ID')}`;

        updateLiveCalculations();
    }

    function updateLiveCalculations() {
        const totalHarga = calculateCurrentTotal();
        const statLiveTotal = document.getElementById('statLiveTotal');
        if (statLiveTotal) statLiveTotal.textContent = 'Rp ' + Number(totalHarga).toLocaleString('id-ID');

        const newPaymentVal = parseFloat(document.getElementById('inputNewPaymentNominal').value) || 0;
        const totalPaidWithNew = existingPaidTotal + newPaymentVal;

        const statPaid = document.getElementById('statSudahDibayar');
        if (statPaid) statPaid.textContent = 'Rp ' + Number(totalPaidWithNew).toLocaleString('id-ID');

        const sisa = Math.max(0, totalHarga - totalPaidWithNew);
        const statSisa = document.getElementById('statLiveSisa');
        const statSisaBox = document.getElementById('statSisaBox');
        const statLiveStatus = document.getElementById('statLiveStatus');

        if (statSisa && statSisaBox) {
            if (sisa <= 0 && totalPaidWithNew > 0) {
                statSisa.textContent = 'Rp 0';
                statSisaBox.className = 'stat-card-item success';
            } else {
                statSisa.textContent = 'Rp ' + Number(sisa).toLocaleString('id-ID');
                statSisaBox.className = 'stat-card-item danger';
            }
        }

        if (statLiveStatus) {
            if (sisa <= 0 && totalPaidWithNew > 0) {
                statLiveStatus.innerHTML = '<span class="badge-status-pill lunas"><i class="fas fa-check-circle"></i> LUNAS</span>';
            } else if (totalPaidWithNew > 0) {
                statLiveStatus.innerHTML = '<span class="badge-status-pill dp"><i class="fas fa-clock"></i> BAYAR DP</span>';
            } else {
                statLiveStatus.innerHTML = '<span class="badge-status-pill belum"><i class="fas fa-times-circle"></i> BELUM BAYAR</span>';
            }
        }
    }

    function formatRupiahNewPayment(inputEl) {
        let val = inputEl.value.replace(/\D/g, ''); // Ambil hanya digit angka
        let realInput = document.getElementById('inputNewPaymentNominal');
        
        if (!val) {
            inputEl.value = '';
            realInput.value = '';
            updateLiveCalculations();
            return;
        }

        realInput.value = val;
        inputEl.value = Number(val).toLocaleString('id-ID');
        updateLiveCalculations();
    }

    function validateItems() {
        if (selectedItems.length === 0) {
            document.getElementById('itemsErrorMsg').style.display = 'block';
            window.scrollTo({ top: 180, behavior: 'smooth' });
            return false;
        }
        return true;
    }

    renderItems();
</script>
@endpush
