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
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .filter-box {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 14px;
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(15,23,36,0.07);
        border-radius: 20px;
        box-shadow: 0 10px 28px rgba(11,35,60,0.06);
        padding: 16px 18px;
        min-width: 0;
        flex: 1;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
        flex: 1;
        min-width: 200px;
    }

    .filter-item label {
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-item select {
        width: 100%;
        border: 2px solid #cbd5e1;
        background: white;
        border-radius: 14px;
        padding: 12px 16px;
        font: inherit;
        font-weight: 800;
        font-size: 15px;
        color: #0f172a;
        min-height: 50px;
        outline: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .filter-item select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(30,136,229,0.15);
    }

    .btn-filter {
        background: linear-gradient(135deg, #1e88e5, #1565c0);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 13px 22px;
        font-size: 15px;
        font-weight: 800;
        min-height: 50px;
        cursor: pointer;
        box-shadow: 0 6px 18px rgba(30,136,229,0.28);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform 0.15s ease;
    }

    .btn-filter:active {
        transform: scale(0.97);
    }

    /* ─── SUMMARY GRID 2x2 ─── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,252,255,0.98));
        border-radius: 16px;
        border: 1px solid rgba(15,23,36,0.05);
        box-shadow: 0 6px 20px rgba(11,35,60,0.04);
        padding: 14px 16px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        border-radius: 0 12px 12px 0;
    }

    .summary-card.pendapatan::before { background: var(--green); }
    .summary-card.piutang::before    { background: var(--red); }
    .summary-card.terjual::before    { background: var(--blue); }
    .summary-card.transaksi::before  { background: #7c3aed; }

    .summary-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: clamp(18px, 1.8vw, 24px);
        font-weight: 900;
        color: var(--text);
        letter-spacing: -0.04em;
        line-height: 1.1;
    }

    .summary-card.pendapatan .summary-value { color: var(--green); }
    .summary-card.piutang .summary-value    { color: var(--red); }
    .summary-card.terjual .summary-value    { color: var(--blue); }
    .summary-card.transaksi .summary-value  { color: #7c3aed; }

    /* GRAFIK PENDAPATAN PANEL */
    .revenue-panel {
        margin-bottom: 22px;
        padding: 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.98));
        border: 1px solid rgba(15, 23, 36, 0.06);
        box-shadow: 0 8px 24px rgba(11,35,60,0.04);
    }

    .revenue-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .revenue-panel-title {
        font-size: 15px;
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
        height: 240px;
        width: 100%;
    }

    .section-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        margin: 14px 0 10px;
    }

    .table-card {
        background: rgba(255,255,255,0.96);
        border-radius: 16px;
        border: 1px solid rgba(15,23,36,0.05);
        box-shadow: 0 8px 24px rgba(11,35,60,0.04);
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
        padding: 12px 14px;
        background: #f8fbff;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 1px solid #edf3f9;
    }

    .tabel-sederhana td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text);
        font-size: 13px;
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
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.04em;
    }

    .status-lunas { background: var(--green-light); color: var(--green); }
    .status-dp    { background: #fef9c3; color: #a16207; }
    .status-belum { background: var(--red-light); color: var(--red); }

    .mobile-list {
        display: none;
    }

    .mobile-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(15,23,36,0.06);
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(11,35,60,0.04);
        padding: 12px 14px;
        margin-bottom: 10px;
    }

    .mobile-head {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 6px;
    }

    .mobile-name {
        font-size: 14px;
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
        gap: 6px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 160px;
        text-align: center;
        color: var(--text-muted);
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(15,23,36,0.05);
        border-radius: 16px;
        padding: 24px 16px;
    }

    .empty-state i {
        font-size: 32px;
        opacity: 0.35;
    }

    /* ─── RESPONSIVE 2X2 MOBILE ─── */
    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px;
        }

        .summary-card {
            padding: 12px;
        }

        .summary-title {
            font-size: 10px;
        }

        .summary-value {
            font-size: 16px;
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
            padding: 10px;
        }
        .filter-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
            width: 100%;
        }
        .filter-item select,
        .btn-filter {
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
    <!-- Panel Filter Bulan & Cabang -->
    <div class="top-panel">
        <form method="GET" action="{{ route('laporan.index') }}" class="filter-box">
            <!-- Filter Bulan -->
            <div class="filter-item">
                <label><i class="fas fa-calendar-alt" style="color: var(--blue);"></i> Bulan:</label>
                <select name="bulan">
                    @for($m = 0; $m < 12; $m++)
                        @php $mo = now()->subMonths($m)->format('Y-m'); @endphp
                        <option value="{{ $mo }}" {{ $bulan === $mo ? 'selected' : '' }}>
                            {{ now()->subMonths($m)->isoFormat('MMMM YYYY') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Filter Cabang (Khusus Admin) -->
            @if(Auth::user()->role === 'admin')
            <div class="filter-item">
                <label><i class="fas fa-store" style="color: var(--blue);"></i> Cabang:</label>
                <select name="cabang">
                    <option value="semua" {{ $cabang === 'semua' ? 'selected' : '' }}>Semua Cabang</option>
                    @foreach($daftarCabang as $c)
                        <option value="{{ $c }}" {{ $cabang === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> TAMPILKAN
            </button>
        </form>
    </div>

    <!-- 4 SUMMARY CARDS (Tampilan 2x2 di Mobile) -->
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
            <div class="summary-value">{{ number_format($totalKg, 1, ',', '.') }} Kg</div>
        </div>
        <div class="summary-card transaksi">
            <div class="summary-title">Jumlah Transaksi</div>
            <div class="summary-value">{{ $totalTransaksi }} Transaksi</div>
        </div>
    </div>

    <!-- Grafik Pendapatan Harian -->
    <div class="revenue-panel">
        <div class="revenue-panel-header">
            <div>
                <div class="revenue-panel-title">Grafik Pendapatan Harian</div>
                <div class="revenue-panel-sub">
                    Periode {{ Carbon\Carbon::parse($bulan)->isoFormat('MMMM YYYY') }} &bull; Cabang: <strong>{{ $cabang === 'semua' ? 'Semua Cabang' : $cabang }}</strong>
                </div>
            </div>
            <div style="font-weight: 800; font-size: 13px; color: var(--green); background: var(--green-light); padding: 5px 12px; border-radius: 999px;">
                Total: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>
        <div class="revenue-chart-wrap">
            <canvas id="laporanRevenueChart"></canvas>
        </div>
    </div>

    <!-- Rincian Transaksi -->
    <div class="section-title">
        Rincian Transaksi ({{ $cabang === 'semua' ? 'Semua Cabang' : $cabang }})
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table class="tabel-sederhana">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Cabang</th>
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
                            <span style="font-size: 11px; font-weight: 700; background: #e0f2fe; color: #0284c7; padding: 3px 8px; border-radius: 6px;">
                                {{ $t->cabangRelasi ? $t->cabangRelasi->nama_cabang : ($t->cabang ?? '-') }}
                            </span>
                        </td>
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
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-file-lines"></i>
                                <div>Tidak ada transaksi pada filter ini.</div>
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
                <span>{{ $t->tanggal_transaksi->format('d/m/Y') }} &bull; {{ $t->cabangRelasi ? $t->cabangRelasi->nama_cabang : ($t->cabang ?? '-') }}</span>
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
            <div>Tidak ada transaksi pada filter ini.</div>
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
