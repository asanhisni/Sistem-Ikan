@extends('layouts.app')

@section('title', 'Struk Transaksi #' . $transaksi->id)
@section('page-title', 'Struk Transaksi')

@push('styles')
<style>
    .struk-page-wrapper {
        max-width: 480px;
        margin: 0 auto 30px;
    }

    /* KARTU STRUK */
    .receipt-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border: 1px solid #e2e8f0;
        padding: 28px 24px;
        position: relative;
        overflow: hidden;
    }

    /* Notch kiri & kanan (thermal look) */
    .receipt-card::before,
    .receipt-card::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        background: #f8fafc;
        border-radius: 50%;
        top: 148px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }
    .receipt-card::before { left: -10px; }
    .receipt-card::after  { right: -10px; }

    /* Header */
    .receipt-header {
        text-align: center;
        padding-bottom: 18px;
        border-bottom: 1.5px dashed #cbd5e1;
    }

    .receipt-header-logo {
        width: 72px;
        height: 72px;
        border-radius: 16px;
        object-fit: contain;
        margin: 0 auto 10px;
        display: block;
    }

    .receipt-store-name {
        font-size: 17px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .receipt-store-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
        font-weight: 600;
    }

    /* Meta Rows */
    .receipt-meta {
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 5px;
        font-size: 13px;
    }

    .receipt-meta-row {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .receipt-meta-row span:first-child {
        color: #94a3b8;
        font-weight: 600;
        min-width: 110px;
    }

    .receipt-meta-row span:last-child, .receipt-meta-row strong {
        color: #0f172a;
        font-weight: 700;
        text-align: right;
    }

    /* Items */
    .receipt-items {
        padding: 14px 0;
        border-bottom: 1.5px dashed #cbd5e1;
    }

    .receipt-items-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 10px;
    }

    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .receipt-item-row:last-child { margin-bottom: 0; }

    .receipt-item-title {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .receipt-item-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 1px;
    }

    .receipt-item-price {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        text-align: right;
        white-space: nowrap;
    }

    /* Totals */
    .receipt-totals {
        padding: 14px 0 4px;
    }

    .receipt-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .receipt-total-row.grand {
        font-size: 17px;
        font-weight: 900;
        color: #0f172a;
        padding-top: 8px;
        border-top: 1.5px solid #f1f5f9;
        margin-top: 4px;
    }

    .receipt-status-badge {
        display: block;
        text-align: center;
        width: 100%;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.05em;
        margin-top: 14px;
        box-sizing: border-box;
    }

    .receipt-status-badge.lunas { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .receipt-status-badge.dp    { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
    .receipt-status-badge.belum { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .receipt-footer-note {
        text-align: center;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-panel {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 18px;
    }

    /* Floating sticky share bar di bawah layar */
    .floating-share-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 999;
        padding: 12px 16px 20px;
        background: linear-gradient(to top, rgba(255,255,255,1) 70%, rgba(255,255,255,0));
    }

    .btn-img-wa {
        background: linear-gradient(135deg, #25d366, #128c7e);
        color: white;
        border: none;
        padding: 16px 18px;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
        cursor: pointer;
        width: 100%;
        transition: transform 0.15s, box-shadow 0.15s;
        min-height: 56px;
    }

    .btn-img-wa:hover  { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(37,211,102,0.45); }
    .btn-img-wa:active { transform: scale(0.98); }
    .btn-img-wa.loading { opacity: 0.8; cursor: wait; }

    /* Pulsing dot untuk status siap */
    .ready-pulse {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #a7f3d0;
        animation: pulse-ring 1.2s infinite;
        flex-shrink: 0;
    }

    @keyframes pulse-ring {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%       { transform: scale(1.5); opacity: 0.6; }
    }

    /* Progress bar render */
    .render-progress-bar {
        height: 3px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .render-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #25d366, #128c7e);
        border-radius: 999px;
        width: 0%;
        transition: width 0.3s ease;
    }

    .btn-secondary-action {
        display: flex;
        gap: 10px;
    }

    .btn-action-small {
        flex: 1;
        background: white;
        border: 1.5px solid #cbd5e1;
        color: #334155;
        text-decoration: none;
        padding: 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-action-small:hover { background: #f8fafc; border-color: #94a3b8; }

    .alert-success-banner {
        background: #edfdf2;
        border: 1px solid #bbf7d0;
        color: #166534;
        padding: 12px 16px;
        border-radius: 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 14px;
    }

    /* Hint Unduh Gambar */
    #downloadHint {
        display: none;
        padding: 10px 14px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #166534;
        text-align: center;
        gap: 8px;
        align-items: center;
        justify-content: center;
    }

    @media print {
        body * { visibility: hidden; }
        .receipt-card, .receipt-card * { visibility: visible; }
        .receipt-card {
            position: absolute;
            left: 0; top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .action-panel, .alert-success-banner { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="struk-page-wrapper">
    @if(session('success'))
    <div class="alert-success-banner">
        <i class="fas fa-check-circle" style="font-size: 20px; color: #22c55e;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- ════ KARTU STRUK ════ -->
    <div class="receipt-card" id="receiptCard">
        <!-- HEADER LOGO + NAMA TOKO -->
        <div class="receipt-header">
            <img src="{{ asset('logo/logo_ikan.png') }}" alt="Logo" class="receipt-header-logo" crossorigin="anonymous" onerror="this.style.display='none'">
            <div class="receipt-store-name">SISTEM IKAN FRESH</div>
            <div class="receipt-store-sub">
                📍 Cabang: {{ $transaksi->cabangRelasi ? $transaksi->cabangRelasi->nama_cabang : ($transaksi->cabang ?? 'Pusat') }}
            </div>
        </div>

        <!-- META INFO TRANSAKSI -->
        <div class="receipt-meta">
            <div class="receipt-meta-row">
                <span>No. Struk</span>
                <strong>#TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</strong>
            </div>
            <div class="receipt-meta-row">
                <span>Tanggal</span>
                <span>{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="receipt-meta-row">
                <span>Pembeli</span>
                <strong>{{ $transaksi->pembeli_nama }}</strong>
            </div>
            @if($transaksi->telepon)
            <div class="receipt-meta-row">
                <span>No. WA/HP</span>
                <span>{{ $transaksi->telepon }}</span>
            </div>
            @endif
        </div>

        <!-- DAFTAR BARANG -->
        <div class="receipt-items">
            <div class="receipt-items-title">Rincian Pesanan</div>

            @if($transaksi->details && $transaksi->details->count() > 0)
                @foreach($transaksi->details as $d)
                <div class="receipt-item-row">
                    <div>
                        <div class="receipt-item-title">{{ $d->ikan ? $d->ikan->nama_ikan : 'Ikan' }}</div>
                        <div class="receipt-item-sub">
                            {{ number_format($d->jumlah_kg, 1, ',', '') }} Kg × Rp {{ number_format($d->harga_per_kg, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="receipt-item-price">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
            @else
                <div class="receipt-item-row">
                    <div>
                        <div class="receipt-item-title">{{ $transaksi->ikan ? $transaksi->ikan->nama_ikan : 'Ikan' }}</div>
                        <div class="receipt-item-sub">
                            {{ number_format($transaksi->jumlah_kg, 1, ',', '') }} Kg × Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="receipt-item-price">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</div>
                </div>
            @endif
        </div>

        <!-- TOTAL + STATUS -->
        <div class="receipt-totals">
            <div class="receipt-total-row">
                <span>Total Volume</span>
                <span>{{ number_format($transaksi->jumlah_kg, 1, ',', '') }} Kg</span>
            </div>

            <div class="receipt-total-row grand">
                <span>TOTAL BAYAR</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>

            @if($transaksi->status === 'dp')
                <div class="receipt-total-row" style="color: #15803d; font-weight: 800;">
                    <span>Dibayar (DP)</span>
                    <span>Rp {{ number_format($transaksi->jumlah_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-total-row" style="color: #b91c1c; font-weight: 800;">
                    <span>Sisa Tagihan</span>
                    <span>Rp {{ number_format($transaksi->total - $transaksi->jumlah_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-status-badge dp"><i class="fas fa-hand-holding-usd"></i> BAYAR DP — BELUM LUNAS</div>
            @elseif($transaksi->status === 'lunas')
                <div class="receipt-status-badge lunas"><i class="fas fa-check-circle"></i> PEMBAYARAN LUNAS ✅</div>
            @else
                <div class="receipt-total-row" style="color: #b91c1c; font-weight: 800; margin-top: 4px;">
                    <span>Tunggakan</span>
                    <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-status-badge belum"><i class="fas fa-times-circle"></i> BELUM DIBAYAR</div>
            @endif

            @if($transaksi->keterangan)
                <p style="margin-top: 10px; font-size: 11px; color: #94a3b8; font-style: italic; text-align: center;">
                    "{{ $transaksi->keterangan }}"
                </p>
            @endif
        </div>

        <div class="receipt-footer-note">
            Terima kasih telah berbelanja ikan segar bersama kami! 🐟🙏
        </div>
    </div>

    <!-- Tombol Sekunder (Cetak, Transaksi Baru, Daftar) -->
    <div class="btn-secondary-action" style="margin-top: 14px; margin-bottom: 80px;">
        <button type="button" class="btn-action-small" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </button>
        <a href="{{ route('transaksi.create') }}" class="btn-action-small" style="background: #e0f2fe; color: #0284c7; border-color: #bae6fd;">
            <i class="fas fa-plus"></i> Transaksi Baru
        </a>
        <a href="{{ route('transaksi.index') }}" class="btn-action-small">
            <i class="fas fa-list"></i> Daftar
        </a>
    </div>
</div>

<!-- ════ FLOATING SHARE BAR (sticky bawah layar) ════ -->
<div class="floating-share-bar">
    <!-- Progress bar saat render berjalan -->
    <div class="render-progress-bar" id="renderProgressBar" style="display: none;">
        <div class="render-progress-fill" id="renderProgressFill"></div>
    </div>

    <button type="button" class="btn-img-wa loading" id="btnBuatGambar" onclick="kirimStrukWA()" disabled>
        <i class="fab fa-whatsapp" style="font-size: 22px;"></i>
        <span id="btnShareText">⏳ Menyiapkan struk...</span>
    </button>
</div>
@php
    // Format No WA (08xx → 628xx)
    $rawPhone = preg_replace('/[^0-9]/', '', $transaksi->telepon ?? '');
    if (str_starts_with($rawPhone, '0')) {
        $formattedWaNumber = '62' . substr($rawPhone, 1);
    } else {
        $formattedWaNumber = $rawPhone;
    }
@endphp
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    const waPhoneNumber = "{{ $formattedWaNumber }}";
    const receiptFileName = "struk-TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}.png";
    const phoneLabel = "{{ $transaksi->telepon ?? '' }}";

    let renderedBlob = null;  // Blob gambar yang sudah dirender
    let isRendering = false;

    // ── Auto-render saat halaman dimuat ──────────────────────────────────────
    window.addEventListener('load', function() {
        // Tunggu 600ms biar halaman tampil dulu
        setTimeout(autoRenderStruk, 600);
    });

    async function autoRenderStruk() {
        if (isRendering) return;
        isRendering = true;

        const btn  = document.getElementById('btnBuatGambar');
        const text = document.getElementById('btnShareText');
        const progressBar  = document.getElementById('renderProgressBar');
        const progressFill = document.getElementById('renderProgressFill');

        // Tampilkan progress bar
        progressBar.style.display = 'block';
        let prog = 0;
        const progTimer = setInterval(() => {
            prog = Math.min(prog + Math.random() * 18, 85);
            progressFill.style.width = prog + '%';
        }, 200);

        try {
            const receiptEl = document.getElementById('receiptCard');
            const canvas = await html2canvas(receiptEl, {
                scale: 2,
                useCORS: true,
                allowTaint: false,
                backgroundColor: '#ffffff',
                logging: false,
                removeContainer: true,
            });

            clearInterval(progTimer);
            progressFill.style.width = '100%';

            canvas.toBlob(function(blob) {
                renderedBlob = blob;

                // Progress selesai, sembunyikan
                setTimeout(() => { progressBar.style.display = 'none'; }, 400);

                // Aktifkan tombol share
                btn.classList.remove('loading');
                btn.disabled = false;

                if (phoneLabel) {
                    text.innerHTML = `<span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE ${phoneLabel} &nbsp;<i class="fab fa-whatsapp"></i>`;
                } else {
                    text.innerHTML = `<span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE WHATSAPP&nbsp;<i class="fab fa-whatsapp"></i>`;
                }

                // Pulse animasi tombol sekali
                btn.style.transform = 'scale(1.02)';
                setTimeout(() => { btn.style.transform = ''; }, 300);

            }, 'image/png', 1.0);

        } catch(err) {
            clearInterval(progTimer);
            console.error('Render error:', err);
            progressBar.style.display = 'none';
            btn.classList.remove('loading');
            btn.disabled = false;
            text.textContent = '⚠️ Coba kirim (tap di sini)';
            // Fallback: render on demand
            btn.onclick = buatGambarDanKirimWA;
        }

        isRendering = false;
    }

    // ── Tombol share yang sudah ready (gambar sudah dirender) ────────────────
    async function kirimStrukWA() {
        if (!renderedBlob) {
            // Gambar belum siap, render dulu
            return buatGambarDanKirimWA();
        }

        const btn  = document.getElementById('btnBuatGambar');
        const text = document.getElementById('btnShareText');

        btn.disabled = true;
        text.textContent = '📤 Membuka WhatsApp...';

        try {
            const file = new File([renderedBlob], receiptFileName, { type: 'image/png' });

            // Web Share API (HP Android/iOS Chrome/Safari)
            if (navigator.share && navigator.canShare && navigator.canShare({ files: [file] })) {
                await navigator.share({
                    files: [file],
                    title: 'Struk Pembelian Ikan',
                    text: 'Berikut struk pembelian Anda. Terima kasih! 🐟',
                });

                text.innerHTML = `✅ Terkirim! <small style="font-weight:600; opacity:.85">(Tap untuk kirim lagi)</small>`;
                btn.disabled = false;
                return;
            }
        } catch(err) {
            // User cancel share sheet → reset
            if (err.name === 'AbortError') {
                btn.disabled = false;
                if (phoneLabel) {
                    text.innerHTML = `<span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE ${phoneLabel} &nbsp;<i class="fab fa-whatsapp"></i>`;
                } else {
                    text.textContent = 'KIRIM STRUK KE WHATSAPP';
                }
                return;
            }
        }

        // Fallback Desktop: unduh gambar + buka WhatsApp
        const url = URL.createObjectURL(renderedBlob);
        const a   = document.createElement('a');
        a.href = url;
        a.download = receiptFileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        setTimeout(() => {
            const waUrl = waPhoneNumber
                ? `https://wa.me/${waPhoneNumber}`
                : `https://wa.me/`;
            window.open(waUrl, '_blank');
        }, 700);

        text.innerHTML = '✅ Gambar tersimpan — WhatsApp dibuka!';
        btn.disabled = false;
    }

    // Fallback render on demand (jika auto-render gagal)
    async function buatGambarDanKirimWA() {
        const btn  = document.getElementById('btnBuatGambar');
        const text = document.getElementById('btnShareText');
        btn.disabled = true;
        text.textContent = '⏳ Membuat gambar...';

        try {
            const receiptEl = document.getElementById('receiptCard');
            const canvas = await html2canvas(receiptEl, {
                scale: 2, useCORS: true, allowTaint: false,
                backgroundColor: '#ffffff', logging: false, removeContainer: true
            });
            canvas.toBlob(blob => {
                renderedBlob = blob;
                btn.disabled = false;
                kirimStrukWA();
            }, 'image/png', 1.0);
        } catch(err) {
            btn.disabled = false;
            text.textContent = '⚠️ Gagal — coba lagi.';
        }
    }
</script>
@endpush
