@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan Penjualan')

@push('styles')
<style>
    .page-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    .top-panel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-box {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.94);
        border: 1px solid rgba(15,23,36,0.06);
        border-radius: 18px;
        box-shadow: 0 10px 24px rgba(11,35,60,0.04);
        padding: 12px 14px;
        min-width: 0;
        flex: 1;
    }

    .filter-box label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }

    .filter-box select {
        border: 1px solid rgba(148,163,184,0.25);
        background: white;
        border-radius: 10px;
        padding: 10px 12px;
        font: inherit;
        font-weight: 600;
        min-height: 42px;
        outline: none;
    }

    .btn-filter {
        background: linear-gradient(135deg, #1e88e5, #1565c0);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 11px 16px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(30,136,229,0.18);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,252,255,0.98));
        border-radius: 18px;
        border: 1px solid rgba(15,23,36,0.05);
        box-shadow: 0 10px 30px rgba(11,35,60,0.04);
        padding: 18px 18px 16px;
        position: relative;
        overflow: hidden;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        border-radius: 0 12px 12px 0;
    }

    .summary-card.pendapatan::before { background: var(--green); }
    .summary-card.piutang::before { background: var(--red); }
    .summary-card.terjual::before { background: var(--blue); }

    .summary-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .summary-value {
        font-size: clamp(24px, 2vw, 30px);
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.05em;
        line-height: 1.1;
    }

    .summary-card.pendapatan .summary-value { color: var(--green); }
    .summary-card.piutang .summary-value { color: var(--red); }
    .summary-card.terjual .summary-value { color: var(--blue); }

    /* GRAFIK PENDAPATAN PANEL */
    .revenue-panel {
        margin-bottom: 24px;
        padding: 20px;
        border-radius: 20px;
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.98));
        border: 1px solid rgba(15, 23, 36, 0.06);
        box-shadow: 0 10px 30px rgba(11,35,60,0.04);
    }

    .revenue-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .revenue-panel-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
    }

    .revenue-panel-sub {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .revenue-chart-wrap {
        position: relative;
        height: 260px;
        width: 100%;
    }

    .section-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin: 14px 0;
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
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text);
        font-size: 14px;
        vertical-align: middle;
    }

    .tabel-sederhana tbody tr:hover {
        background: #fafcff;
    }

    .status-lunas,
    .status-belum,
    .status-dp {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
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
        background: #fef9c3;
        color: #a16207;
    }

    .status-belum {
        background: var(--red-light);
        color: var(--red);
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
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
    }

    .mobile-name {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
    }

    .mobile-total {
        font-size: 14px;
        font-weight: 800;
        color: var(--blue);
    }

    .mobile-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 10px;
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
        padding: 24px 18px;
    }

    .empty-state i {
        font-size: 38px;
        opacity: 0.35;
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .top-panel {
            display: block;
        }
        .filter-box {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .filter-box select,
        .filter-box .btn-filter {
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
    <div class="top-panel">
        <form method="GET" action="{{ route('laporan.index') }}" class="filter-box">
            <label>Pilih Bulan Laporan:</label>
            <select name="bulan">
                @for($m = 0; $m < 12; $m++)
                    @php $mo = now()->subMonths($m)->format('Y-m'); @endphp
                    <option value="{{ $mo }}" {{ $bulan === $mo ? 'selected' : '' }}>
                        {{ now()->subMonths($m)->isoFormat('MMMM YYYY') }}
                    </option>
                @endfor
            </select>
            <button type="submit" class="btn-filter">LIHAT LAPORAN</button>
        </form>

        @if(Auth::user()->role === 'kepala_cabang')
        <button class="btn-filter" onclick="alert('Laporan bulan ini telah dikirim ke Pusat secara otomatis!')">
            <i class="fas fa-paper-plane"></i> KIRIM LAPORAN
        </button>
        @endif
    </div>

    <div class="summary-grid">
        <div class="summary-card pendapatan">
            <div class="summary-title">Pendapatan (Lunas)</div>
            <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card piutang">
            <div class="summary-title">Piutang (Belum Lunas)</div>
            <div class="summary-value">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card terjual">
            <div class="summary-title">Total Ikan Terjual</div>
            <div class="summary-value">{{ number_format($totalKg, 1, ',', '') }} Kg</div>
        </div>
    </div>

    <!-- Grafik Pendapatan Pindahan dari Dashboard -->
    <div class="revenue-panel">
        <div class="revenue-panel-header">
            <div>
                <div class="revenue-panel-title">Grafik Pendapatan Harian</div>
                <div class="revenue-panel-sub">Perkembangan total omset penjualan selama periode {{ Carbon\Carbon::parse($bulan)->isoFormat('MMMM YYYY') }}</div>
            </div>
            <div style="font-weight: 800; font-size: 14px; color: var(--green); background: var(--green-light); padding: 6px 12px; border-radius: 999px;">
                Total: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>
        <div class="revenue-chart-wrap">
            <canvas id="laporanRevenueChart"></canvas>
        </div>
    </div>

    <div class="section-title">Rincian Transaksi Bulan Ini</div>

    <div class="table-card">
        <div class="table-wrapper">
            <table class="tabel-sederhana">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Ikan</th>
                        <th>Kg</th>
                        <th>Total (Rp)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr>
                        <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td style="font-weight: 700;">{{ $t->pembeli_nama }}</td>
                        <td>
                            @if($t->details && $t->details->count() > 0)
                                @foreach($t->details as $d)
                                    <div>{{ $d->ikan ? $d->ikan->nama_ikan : '-' }} ({{ $d->jumlah_kg }} Kg)</div>
                                @endforeach
                            @else
                                {{ $t->ikan ? $t->ikan->nama_ikan : '-' }}
                            @endif
                        </td>
                        <td>{{ $t->jumlah_kg }}</td>
                        <td style="font-weight: 700;">{{ number_format($t->total, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status == 'lunas')
                                <span class="status-lunas">LUNAS</span>
                            @elseif($t->status == 'dp')
                                <span class="status-dp">DP</span>
                            @else
                                <span class="status-belum">BELUM</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-file-lines"></i>
                                <div>Tidak ada transaksi di bulan ini.</div>
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
                <div class="mobile-name">{{ $t->pembeli_nama }}</div>
                <div class="mobile-total">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
            </div>
            <div class="mobile-meta">
                <span>{{ $t->tanggal_transaksi->format('d/m/Y') }}</span>
                <span>Total: {{ $t->jumlah_kg }} Kg</span>
            </div>
            <div class="mobile-meta" style="flex-direction: column; gap: 4px;">
                @if($t->details && $t->details->count() > 0)
                    @foreach($t->details as $d)
                        <div>&bull; {{ $d->ikan ? $d->ikan->nama_ikan : '-' }} ({{ $d->jumlah_kg }} Kg)</div>
                    @endforeach
                @else
                    <span>{{ $t->ikan ? $t->ikan->nama_ikan : '-' }}</span>
                @endif
                <div style="margin-top: 6px;">
                    @if($t->status == 'lunas')
                        <span class="status-lunas">LUNAS</span>
                    @elseif($t->status == 'dp')
                        <span class="status-dp">DP</span>
                    @else
                        <span class="status-belum">BELUM</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-file-lines"></i>
            <div>Tidak ada transaksi di bulan ini.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chartLabels = @json($chartLabels ?? []);
    const chartValues = @json($chartValues ?? []);

    if (document.getElementById('laporanRevenueChart') && chartLabels.length > 0) {
        const ctx = document.getElementById('laporanRevenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan',
                    data: chartValues,
                    borderRadius: 6,
                    backgroundColor: 'rgba(30, 136, 229, 0.8)',
                    borderColor: '#1e88e5',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Rp ' + Number(ctx.raw).toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 11, weight: '600' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148,163,184,0.12)' },
                        ticks: {
                            callback: value => 'Rp ' + Number(value).toLocaleString('id-ID'),
                            color: '#6b7280',
                            font: { size: 11, weight: '600' }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
