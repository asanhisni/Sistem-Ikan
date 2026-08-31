@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')
@section('page-title', 'Form Transaksi Baru')

@push('styles')
<style>
    .form-container {
        background: white;
        max-width: 720px;
        margin: 0 auto;
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
        margin-bottom: 22px;
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
        color: #1e88e5;
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
        border-color: #1e88e5;
        box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.12);
    }

    /* ─── UI PEMILIHAN IKAN CEPAT (GRID CHIPS) ─── */
    .ikan-picker-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
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
        border-color: #1e88e5;
        background: #f0f9ff;
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
        color: #1e88e5;
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
        background: linear-gradient(135deg, #1e88e5, #1565c0);
        color: white;
        border-radius: 14px;
        padding: 14px 18px;
        margin-top: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 16px rgba(30, 136, 229, 0.25);
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

    /* ─── STATUS RADIO CARDS ─── */
    .radio-group {
        display: flex;
        gap: 10px;
    }
    
    .radio-card {
        flex: 1;
        position: relative;
    }
    
    .radio-card input {
        position: absolute;
        opacity: 0;
    }
    
    .radio-card label {
        display: block;
        padding: 14px 10px;
        text-align: center;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 800;
        color: #64748b;
        transition: all 0.2s;
        margin: 0;
    }
    
    .radio-card input:checked + label.lunas-lbl {
        background: #dcfce7;
        border-color: #22c55e;
        color: #15803d;
    }
    
    .radio-card input:checked + label.dp-lbl {
        background: #fef9c3;
        border-color: #eab308;
        color: #a16207;
    }

    .radio-card input:checked + label.belum-lbl {
        background: #fee2e2;
        border-color: #ef4444;
        color: #b91c1c;
    }
    
    .dp-input-box {
        display: none;
        margin-top: 14px;
        background: #fefce8;
        padding: 14px;
        border-radius: 12px;
        border: 1.5px dashed #facc15;
    }
    
    .btn-simpan {
        width: 100%;
        background: linear-gradient(135deg, #1e88e5, #1565c0);
        color: white;
        padding: 16px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(30, 136, 229, 0.3);
        margin-top: 10px;
        transition: transform 0.15s;
    }
    
    .btn-simpan:hover {
        background: #1565c0;
        transform: translateY(-1px);
    }
    
    .btn-batal {
        display: block;
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
    }
    
    .error-msg {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        font-weight: 700;
    }

    /* Modal Dialog Volume Prompt */
    .prompt-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .prompt-modal-overlay.show {
        display: flex;
    }

    .prompt-modal-card {
        background: white;
        border-radius: 20px;
        padding: 24px 20px;
        max-width: 380px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        animation: modalScaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalScaleIn {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
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
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .prompt-modal-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .prompt-modal-subtitle {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-top: 3px;
    }

    .prompt-modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-prompt-submit {
        flex: 1;
        background: #1e88e5;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 13px;
        font-weight: 800;
        font-size: 15px;
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
    <form action="{{ route('transaksi.store') }}" method="POST" id="transaksiForm" onsubmit="return validateItems()">
        @csrf
        
        <!-- 1. Pembeli -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-user"></i>
                <span>1. Nama Pembeli</span>
            </label>
            <input type="text" name="pembeli_nama" class="form-control" placeholder="Contoh: Pak Budi / Toko Laris" value="{{ old('pembeli_nama') }}" required autocomplete="off">
            @error('pembeli_nama') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        
        <!-- 2. Multi Jenis Ikan & Volume (Sederhana & Cepat) -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-fish"></i>
                <span>2. Pilih Ikan & Masukkan Kilogram</span>
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

                <!-- Daftar Ikan yang Masuk Pesanan (Card Style, Mudah Digeser & Dilihat di Mobile) -->
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
        </div>
        
        <!-- 3. Status Pembayaran -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-money-bill-wave"></i>
                <span>3. Status Pembayaran</span>
            </label>
            <div class="radio-group" style="flex-wrap: wrap;">
                <div class="radio-card" style="min-width: 140px;">
                    <input type="radio" id="st_lunas" name="status" value="lunas" {{ old('status', 'lunas') == 'lunas' ? 'checked' : '' }} onchange="toggleDpInput()">
                    <label for="st_lunas" class="lunas-lbl"><i class="fas fa-check-circle"></i> LUNAS</label>
                </div>
                <div class="radio-card" style="min-width: 140px;">
                    <input type="radio" id="st_dp" name="status" value="dp" {{ old('status') == 'dp' ? 'checked' : '' }} onchange="toggleDpInput()">
                    <label for="st_dp" class="dp-lbl"><i class="fas fa-hand-holding-usd"></i> BAYAR DP</label>
                </div>
                <div class="radio-card" style="min-width: 140px;">
                    <input type="radio" id="st_belum" name="status" value="belum_lunas" {{ old('status') == 'belum_lunas' ? 'checked' : '' }} onchange="toggleDpInput()">
                    <label for="st_belum" class="belum-lbl"><i class="fas fa-times-circle"></i> BELUM</label>
                </div>
            </div>
            @error('status') <div class="error-msg">{{ $message }}</div> @enderror
            
            <div class="dp-input-box" id="dp-box">
                <label style="color: #854d0e; font-size: 13px; font-weight: 700; margin-bottom: 6px; display: block;">Jumlah Uang Muka / DP yang Diterima (Rp)</label>
                <input type="number" name="jumlah_dibayar" class="form-control" placeholder="Contoh: 500000" value="{{ old('jumlah_dibayar') }}">
            </div>
        </div>
        
        <!-- 4. Keterangan -->
        <div class="form-group">
            <label class="section-label">
                <i class="fas fa-pencil-alt"></i>
                <span>4. Catatan Tambahan (Opsional)</span>
            </label>
            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Diambil sore hari" value="{{ old('keterangan') }}">
        </div>
        
        <button type="submit" class="btn-simpan"><i class="fas fa-check"></i> SIMPAN TRANSAKSI</button>
        <a href="{{ route('transaksi.index') }}" class="btn-batal">KEMBALI</a>
    </form>
</div>

<!-- Modal Prompt Volume (Kg) -->
<div class="prompt-modal-overlay" id="promptVolumeModal">
    <div class="prompt-modal-card">
        <div class="prompt-modal-header">
            <div class="prompt-modal-icon">
                <i class="fas fa-weight-hanging"></i>
            </div>
            <div>
                <div class="prompt-modal-title" id="promptNamaIkan">Nama Ikan</div>
                <div class="prompt-modal-subtitle" id="promptHargaIkan">Rp 0 / Kg</div>
            </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Berapa Kg yang dibeli?</label>
            <input type="number" step="0.1" min="0.1" id="promptJumlahKg" class="form-control" placeholder="Contoh: 10 atau 12.5" onkeydown="if(event.key==='Enter'){ event.preventDefault(); confirmAddIkan(); }">
            <div id="promptErrorMsg" class="error-msg" style="display: none;">Masukkan angka Kg yang valid (min. 0.1 Kg).</div>
        </div>

        <div class="prompt-modal-actions">
            <button type="button" class="btn-prompt-cancel" onclick="closePromptModal()">Batal</button>
            <button type="button" class="btn-prompt-submit" onclick="confirmAddIkan()">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let selectedItems = [];
    let pendingIkan = null;

    function openVolumePrompt(id, nama, harga) {
        pendingIkan = { id, nama, harga: parseFloat(harga) || 0 };

        document.getElementById('promptNamaIkan').textContent = pendingIkan.nama;
        document.getElementById('promptHargaIkan').textContent = 'Rp ' + Number(pendingIkan.harga).toLocaleString('id-ID') + ' / Kg';
        document.getElementById('promptJumlahKg').value = '';
        document.getElementById('promptErrorMsg').style.display = 'none';
        
        const modal = document.getElementById('promptVolumeModal');
        modal.classList.add('show');
        setTimeout(() => {
            document.getElementById('promptJumlahKg').focus();
        }, 100);
    }

    function closePromptModal() {
        document.getElementById('promptVolumeModal').classList.remove('show');
        pendingIkan = null;
    }

    function confirmAddIkan() {
        const kgInput = document.getElementById('promptJumlahKg');
        const kg = parseFloat(kgInput.value);

        if (!kg || kg <= 0) {
            document.getElementById('promptErrorMsg').style.display = 'block';
            kgInput.focus();
            return;
        }

        const existingIndex = selectedItems.findIndex(i => String(i.id) === String(pendingIkan.id));
        if (existingIndex > -1) {
            selectedItems[existingIndex].kg += kg;
        } else {
            selectedItems.push({
                id: pendingIkan.id,
                nama: pendingIkan.nama,
                harga: pendingIkan.harga,
                kg: kg
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
            return;
        }

        emptyPlaceholder.style.display = 'none';
        banner.style.display = 'flex';
        errorMsg.style.display = 'none';
        labelCount.textContent = `(${selectedItems.length} jenis)`;

        let html = '';
        selectedItems.forEach((item, idx) => {
            const subtotal = item.harga * item.kg;
            html += `
                <div class="item-card">
                    <div class="item-card-top">
                        <div>
                            <div class="item-card-title">${item.nama}</div>
                            <div style="font-size: 12px; color: #64748b;">Rp ${Number(item.harga).toLocaleString('id-ID')} / Kg</div>
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
                        <button type="button" class="btn-item-delete" onclick="removeIkanItem(${idx})" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        updateTotalsOnly();
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
    }

    function validateItems() {
        if (selectedItems.length === 0) {
            document.getElementById('itemsErrorMsg').style.display = 'block';
            window.scrollTo({ top: 180, behavior: 'smooth' });
            return false;
        }
        return true;
    }

    function toggleDpInput() {
        var isDp = document.getElementById('st_dp').checked;
        document.getElementById('dp-box').style.display = isDp ? 'block' : 'none';
    }

    renderItems();
    toggleDpInput();
</script>
@endpush
