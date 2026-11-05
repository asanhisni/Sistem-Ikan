@extends('layouts.app')

@section('title', 'Struk Transaksi #' . $transaksi->id)
@section('page-title', 'Struk Transaksi')

@push('styles')
<style>
    .struk-page-wrapper {
        max-width: 375px;
        margin: 0 auto 24px;
    }

    /* KARTU STRUK */
    .receipt-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.07);
        border: 1px solid #e2e8f0;
        padding: 22px 18px;
        position: relative;
        overflow: hidden;
    }

    /* Notch kiri & kanan (thermal look) */
    .receipt-card::before,
    .receipt-card::after {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        background: #f8fafc;
        border-radius: 50%;
        top: 125px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
    }
    .receipt-card::before { left: -9px; }
    .receipt-card::after  { right: -9px; }

    /* Header */
    .receipt-header {
        text-align: center;
        padding-bottom: 14px;
        border-bottom: 1.5px dashed #cbd5e1;
    }

    .receipt-header-logo {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: contain;
        margin: 0 auto 8px;
        display: block;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        padding: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .receipt-store-title {
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 2px;
    }

    .receipt-store-name {
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .receipt-store-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
        font-weight: 600;
    }

    /* Meta Rows */
    .receipt-meta {
        padding: 12px 0;
        border-bottom: 1.5px dashed #cbd5e1;
        display: flex;
        flex-direction: column;
        gap: 5px;
        font-size: 12px;
    }

    .receipt-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .receipt-meta-row span:first-child {
        color: #64748b;
        font-weight: 600;
        min-width: 80px;
    }

    .receipt-meta-row span:last-child, .receipt-meta-row strong {
        color: #0f172a;
        font-weight: 750;
        text-align: right;
    }

    /* Items */
    .receipt-items {
        padding: 12px 0;
        border-bottom: 1.5px dashed #cbd5e1;
    }

    .receipt-items-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
        gap: 8px;
    }

    .receipt-item-row:last-child { margin-bottom: 0; }

    .receipt-item-title {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
    }

    .receipt-item-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 1px;
        font-weight: 550;
    }

    .receipt-item-price {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        text-align: right;
        white-space: nowrap;
    }

    /* Riwayat Pembayaran / Cicilan */
    .receipt-payments {
        padding: 12px 0;
        border-bottom: 1.5px dashed #cbd5e1;
    }

    .receipt-payments-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .receipt-payment-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        font-size: 12px;
        gap: 8px;
    }

    .receipt-payment-row:last-child {
        margin-bottom: 0;
    }

    .receipt-payment-info {
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .receipt-payment-num {
        font-size: 10px;
        font-weight: 800;
        background: #e0f2fe;
        color: #0369a1;
        padding: 1px 5px;
        border-radius: 4px;
    }

    .receipt-payment-note {
        font-size: 10.5px;
        color: #64748b;
        margin-top: 1px;
        padding-left: 20px;
        font-weight: 500;
    }

    .receipt-payment-nominal {
        font-size: 12px;
        font-weight: 800;
        color: #15803d;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 2px 6px;
        border-radius: 5px;
        white-space: nowrap;
        text-align: right;
    }

    /* Totals */
    .receipt-totals {
        padding: 12px 0 2px;
    }

    .receipt-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #475569;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .receipt-total-row.grand {
        font-size: 14.5px;
        font-weight: 900;
        color: #0f172a;
        padding: 7px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #f1f5f9;
        margin: 5px 0 7px;
    }

    .receipt-status-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-align: center;
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.02em;
        margin-top: 10px;
        box-sizing: border-box;
    }

    .receipt-status-badge.lunas { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .receipt-status-badge.dp    { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
    .receipt-status-badge.belum { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .receipt-notes-box {
        background: #fefce8;
        border: 1px dashed #facc15;
        border-radius: 8px;
        padding: 8px 10px;
        margin-top: 10px;
        font-size: 11px;
        color: #854d0e;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        line-height: 1.35;
    }

    .receipt-footer-note {
        text-align: center;
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1.5px dashed #cbd5e1;
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-panel {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 18px;
    }

    /* Floating sticky share bar di bawah layar (di atas bottom nav) */
    .floating-share-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1050;
        padding: 12px 16px 16px;
        background: linear-gradient(to top, rgba(255,255,255,1) 75%, rgba(255,255,255,0));
        max-width: 480px;
        margin: 0 auto;
    }

    @media (max-width: 640px) {
        .floating-share-bar {
            bottom: 64px; /* Naikkan tepat di atas mobile bottom-nav */
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06);
        }
        .struk-page-wrapper {
            padding-bottom: 140px; /* Jarak aman scroll di mobile */
        }
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
            <img src="{{ asset('logo/logo_ikan2.png') }}" alt="Logo" class="receipt-header-logo" crossorigin="anonymous" onerror="this.style.display='none'">
            <div style="font-size: 13px; font-weight: 800; letter-spacing: 0.06em; color: #475569; margin-bottom: 2px;">STRUK PEMBELIAN IKAN</div>
            <div class="receipt-store-name">PT. PELOPOR SAMI JAYA</div>
            <div class="receipt-store-sub">
                Cabang: {{ $transaksi->cabangRelasi ? $transaksi->cabangRelasi->nama_cabang : ($transaksi->cabang ?? 'Pusat') }}
            </div>
        </div>

        <!-- META INFO TRANSAKSI -->
        <div class="receipt-meta">
            <div style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-bottom: 4px;">DATA TRANSAKSI</div>
            <div class="receipt-meta-row">
                <span>No. Struk</span>
                <strong>#TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</strong>
            </div>
            <div class="receipt-meta-row">
                <span>Tanggal</span>
                <span>{{ $transaksi->tanggal_transaksi->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
            <div class="receipt-meta-row">
                <span>Pembeli</span>
                <strong>{{ $transaksi->pembeli_nama }}</strong>
            </div>
            @if($transaksi->telepon)
            <div class="receipt-meta-row">
                <span>No. HP/WA</span>
                <span>{{ $transaksi->telepon }}</span>
            </div>
            @endif
        </div>

        <!-- DAFTAR BARANG -->
        <div class="receipt-items">
            <div class="receipt-items-title">RINCIAN PESANAN</div>

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

        <!-- RIWAYAT PEMBAYARAN & CICILAN -->
        @if($transaksi->pembayarans && $transaksi->pembayarans->count() > 0)
        <div class="receipt-payments">
            <div class="receipt-payments-title">
                <span>RIWAYAT PEMBAYARAN / CICILAN</span>
                <span>{{ $transaksi->pembayarans->count() }}x Bayar</span>
            </div>
            @foreach($transaksi->pembayarans as $idx => $p)
            <div class="receipt-payment-row">
                <div>
                    <div class="receipt-payment-info">
                        <span class="receipt-payment-num">#{{ $idx + 1 }}</span>
                        <span>{{ $p->tanggal_bayar->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    @if($p->keterangan && $p->keterangan !== 'Pembayaran Cicilan')
                        <div class="receipt-payment-note">{{ $p->keterangan }}</div>
                    @endif
                </div>
                <div class="receipt-payment-nominal">+ Rp {{ number_format($p->nominal, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- TOTAL + STATUS -->
        <div class="receipt-totals">
            <div style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-bottom: 10px;">RINGKASAN PEMBAYARAN</div>
            <div class="receipt-total-row">
                <span>Total Berat</span>
                <span>{{ number_format($transaksi->jumlah_kg, 1, ',', '') }} Kg</span>
            </div>

            <div class="receipt-total-row grand">
                <span>TOTAL BELANJA</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>

            @php
                $totalSudahBayar = $transaksi->total_dibayar;
                $sisaTagihan = $transaksi->sisa_tagihan;
            @endphp

            <div class="receipt-total-row" style="color: #15803d; font-weight: 700; margin-top: 4px;">
                <span>Sudah Dibayar</span>
                <span>Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</span>
            </div>

            @if($sisaTagihan > 0)
                <div class="receipt-total-row" style="color: #b91c1c; font-weight: 800;">
                    <span>SISA TAGIHAN</span>
                    <span>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                </div>
            @endif

            @if($transaksi->status === 'lunas' || ($sisaTagihan <= 0 && $totalSudahBayar > 0))
                <div class="receipt-status-badge lunas"><i class="fas fa-check-circle"></i> STATUS : PEMBAYARAN LUNAS</div>
            @elseif($transaksi->status === 'dp' || $totalSudahBayar > 0)
                <div class="receipt-status-badge dp"><i class="fas fa-hand-holding-usd"></i> STATUS : BAYAR DP — BELUM LUNAS</div>
            @else
                <div class="receipt-status-badge belum"><i class="fas fa-times-circle"></i> STATUS : BELUM DIBAYAR</div>
            @endif

            @if($transaksi->keterangan)
                <div class="receipt-notes-box">
                    <i class="fas fa-comment-alt" style="margin-top: 2px;"></i>
                    <div>
                        <strong>Catatan:</strong> {{ $transaksi->keterangan }}
                    </div>
                </div>
            @endif
        </div>

        <div class="receipt-footer-note">
            <div>Terima kasih telah berbelanja di</div>
            <div style="font-weight: 800; color: #0f172a; margin-top: 2px;">PT. PELOPOR SAMI JAYA</div>
        </div>
    </div>

    <!-- Tombol Sekunder (Cetak, Transaksi Baru, Daftar) -->
    <div class="btn-secondary-action" style="margin-top: 14px; margin-bottom: 80px;">
        <button type="button" class="btn-action-small" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </button>
        @if(Auth::user()->role !== 'admin')
        <a href="{{ route('transaksi.create') }}" class="btn-action-small" style="background: #e0f2fe; color: #0284c7; border-color: #bae6fd;">
            <i class="fas fa-plus"></i> Transaksi Baru
        </a>
        @endif
        <a href="{{ route('transaksi.index') }}" class="btn-action-small">
            <i class="fas fa-list"></i> Daftar
        </a>
    </div>
</div>

<!-- ════ FLOATING SHARE BAR (sticky bawah layar) ════ -->
<div class="floating-share-bar">
    <button type="button" class="btn-img-wa" id="btnBuatGambar" onclick="kirimStrukWA()">
        <i class="fab fa-whatsapp" style="font-size: 22px;"></i>
        <span id="btnShareText">
            @if($transaksi->telepon)
                <span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE {{ $transaksi->telepon }}&nbsp;<i class="fab fa-whatsapp"></i>
            @else
                <span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE WHATSAPP
            @endif
        </span>
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
<script>
    const waPhoneNumber = "{{ $formattedWaNumber }}";
    const phoneLabel    = "{{ $transaksi->telepon ?? '' }}";

    function kirimStrukWA() {
        const btn  = document.getElementById('btnBuatGambar');
        const text = document.getElementById('btnShareText');

        // ── Bangun teks struk sesuai format rapi ──
        @php
            $cabangNama = $transaksi->cabangRelasi ? $transaksi->cabangRelasi->nama_cabang : ($transaksi->cabang ?? 'Pusat');
            $noStruk    = '#TRX-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT);
            $tgl        = $transaksi->tanggal_transaksi->locale('id')->isoFormat('dddd, D MMMM Y');
        @endphp

        const lineDbl = '════════════════════════';
        const lineSgl = '────────────────────────';

        function centerText(str, width = 24) {
            str = str.trim();
            if (str.length >= width) return str;
            const padLeft = Math.floor((width - str.length) / 2);
            return ' '.repeat(padLeft) + str;
        }

        let teks = '';
        teks += lineDbl + '\n';
        teks += centerText('STRUK PEMBELIAN IKAN') + '\n';
        teks += centerText('PT. PELOPOR SAMI JAYA') + '\n';
        teks += centerText('Cabang: {{ $cabangNama }}') + '\n';
        teks += lineDbl + '\n';

        teks += 'DATA TRANSAKSI\n';
        teks += `No. Struk : {{ $noStruk }}\n`;
        teks += `Tanggal   : {{ $tgl }}\n`;
        teks += `Pembeli   : {{ $transaksi->pembeli_nama }}\n`;
        @if($transaksi->telepon)
        teks += `No. HP/WA : {{ $transaksi->telepon }}\n`;
        @endif

        teks += lineSgl + '\n';
        teks += 'RINCIAN PESANAN\n';
        @if($transaksi->details && $transaksi->details->count() > 0)
            @foreach($transaksi->details as $d)
            teks += `{{ $d->ikan ? $d->ikan->nama_ikan : 'Ikan' }}\n`;
            teks += `• {{ number_format($d->jumlah_kg, 1, ',', '') }} Kg × Rp {{ number_format($d->harga_per_kg, 0, ',', '.') }} = Rp {{ number_format($d->subtotal, 0, ',', '.') }}\n`;
            @endforeach
        @else
            teks += `{{ $transaksi->ikan ? $transaksi->ikan->nama_ikan : 'Ikan' }}\n`;
            teks += `• {{ number_format($transaksi->jumlah_kg, 1, ',', '') }} Kg × Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }} = Rp {{ number_format($transaksi->total, 0, ',', '.') }}\n`;
        @endif

        @if($transaksi->pembayarans && $transaksi->pembayarans->count() > 0)
        teks += lineSgl + '\n';
        teks += 'RIWAYAT PEMBAYARAN\n';
        @foreach($transaksi->pembayarans as $idx => $p)
        teks += `{{ $idx + 1 }}. {{ $p->tanggal_bayar->locale('id')->isoFormat('dddd, D MMMM Y') }}\n`;
        teks += `   + Rp {{ number_format($p->nominal, 0, ',', '.') }}{{ ($p->keterangan && $p->keterangan !== 'Pembayaran Cicilan') ? ' (' . $p->keterangan . ')' : '' }}\n`;
        @endforeach
        @endif

        teks += lineDbl + '\n';
        teks += 'RINGKASAN PEMBAYARAN\n';
        teks += `Total Berat   : {{ number_format($transaksi->jumlah_kg, 1, ',', '') }} Kg\n`;
        teks += `TOTAL BELANJA : Rp {{ number_format($transaksi->total, 0, ',', '.') }}\n`;
        teks += `Sudah Dibayar : Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}\n`;
        @if($sisaTagihan > 0)
        teks += `SISA TAGIHAN  : Rp {{ number_format($sisaTagihan, 0, ',', '.') }}\n`;
        @endif

        @if($transaksi->status === 'lunas' || ($sisaTagihan <= 0 && $totalSudahBayar > 0))
        teks += 'STATUS : PEMBAYARAN LUNAS\n';
        @elseif($transaksi->status === 'dp' || $totalSudahBayar > 0)
        teks += 'STATUS : BAYAR DP — BELUM LUNAS\n';
        @else
        teks += 'STATUS : BELUM DIBAYAR\n';
        @endif

        @if($transaksi->keterangan)
        teks += `Catatan: {{ $transaksi->keterangan }}\n`;
        @endif

        teks += lineDbl + '\n';
        teks += 'Terima kasih telah belanja di\n';
        teks += centerText('PT. PELOPOR SAMI JAYA');

        // ── Buka WhatsApp dengan teks ──
        const encoded = encodeURIComponent(teks);
        const waUrl = waPhoneNumber
            ? `https://wa.me/${waPhoneNumber}?text=${encoded}`
            : `https://wa.me/?text=${encoded}`;

        window.open(waUrl, '_blank');

        // Feedback tombol
        text.innerHTML = '✅ WhatsApp dibuka!';
        btn.disabled = true;
        setTimeout(() => {
            btn.disabled = false;
            if (phoneLabel) {
                text.innerHTML = `<span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE ${phoneLabel}&nbsp;<i class="fab fa-whatsapp"></i>`;
            } else {
                text.innerHTML = `<span class="ready-pulse"></span>&nbsp;KIRIM STRUK KE WHATSAPP`;
            }
        }, 3000);
    }
</script>
@endpush
