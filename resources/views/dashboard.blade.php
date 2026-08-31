@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    :root {
        --soft-blue: rgba(30,136,229,0.12);
        --soft-red: rgba(229,57,53,0.1);
        --soft-green: rgba(67,160,71,0.12);
        --soft-teal: rgba(0,137,123,0.1);
    }

    .content-inner {
        max-width: 1180px;
        margin: 0 auto;
        padding-bottom: 12px;
    }

    .welcome-strip {
        margin-bottom: 18px;
        padding: 20px 20px 16px;
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(240,247,255,0.95));
        border: 1px solid rgba(30,136,229,0.08);
        box-shadow: 0 10px 28px rgba(15, 23, 36, 0.04);
    }

    .welcome-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    .welcome-title {
        font-size: clamp(22px, 2vw, 30px);
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--text);
        line-height: 1.2;
    }

    .welcome-sub {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 6px;
        letter-spacing: 0.01em;
    }

    .welcome-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 12px;
    }

    .welcome-meta .meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,0.7);
        border: 1px solid rgba(148,163,184,0.14);
    }

    .filters-panel {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 18px;
        background: rgba(255,255,255,0.72);
        border: 1px solid rgba(15, 23, 36, 0.04);
        box-shadow: 0 6px 20px rgba(11,35,60,0.03);
    }

    .filters-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .filter-select {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-width: 180px;
        padding: 10px 12px;
        border-radius: 12px;
        background: var(--white);
        border: 1px solid rgba(148,163,184,0.18);
        box-shadow: 0 4px 16px rgba(15, 23, 36, 0.04);
        transition: all 0.2s ease;
    }

    .filter-select:hover {
        border-color: rgba(30,136,229,0.3);
    }

    .filter-select i {
        color: var(--blue);
        font-size: 13px;
        width: 16px;
        text-align: center;
    }

    .filter-select select {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        padding-right: 6px;
    }

    .filter-select .chevron {
        opacity: 0.7;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
        align-items: stretch;
    }

    .stat-card {
        position: relative;
        padding: 18px 18px 16px;
        border-radius: 18px;
        min-height: 172px;
        background: linear-gradient(180deg, rgba(255,255,255,0.985), rgba(250,252,255,0.96));
        border: 1px solid rgba(15, 23, 36, 0.05);
        box-shadow: 0 8px 22px rgba(11,35,60,0.06);
        overflow: hidden;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(11,35,60,0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        border-radius: 12px 0 0 12px;
    }

    .stat-card.blue::before { background: var(--blue); }
    .stat-card.red::before { background: var(--red); }
    .stat-card.green::before { background: var(--green); }
    .stat-card.teal::before { background: #00897b; }

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        margin-bottom: 16px;
        font-size: 20px;
        border: 1px solid transparent;
    }

    .stat-icon-wrap.blue { background: var(--blue-light); color: var(--blue); }
    .stat-icon-wrap.red { background: var(--red-light); color: var(--red); }
    .stat-icon-wrap.green { background: var(--green-light); color: var(--green); }
    .stat-icon-wrap.teal { background: rgba(0,137,123,0.08); color: #00897b; }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: clamp(26px, 2vw, 34px);
        font-weight: 800;
        letter-spacing: -0.05em;
        color: var(--text);
        line-height: 1.08;
        word-break: break-word;
    }

    .stat-value.blue { color: var(--blue); }
    .stat-value.red { color: var(--red); }
    .stat-value.green { color: var(--green); }

    .stat-value.tiny {
        font-size: clamp(22px, 1.8vw, 26px);
    }

    .chart-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chart-card .stat-card-header {
        margin-bottom: 8px;
    }

    .penjualan-value {
        font-size: clamp(24px, 2vw, 30px);
        font-weight: 800;
        letter-spacing: -0.05em;
        color: var(--text);
        margin: 8px 0 10px;
    }

    .growth-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
    }

    .growth-badge.up { background: var(--green-light); color: var(--green); }
    .growth-badge.down { background: var(--red-light); color: var(--red); }
    .growth-badge.flat { background: #f3f4f6; color: #6b7a8d; }

    .chart-area {
        height: 90px;
        margin-top: 8px;
    }

    .revenue-panel {
        margin: 8px 0 22px;
        padding: 18px 18px 16px;
        border-radius: 20px;
        background: linear-gradient(180deg, rgba(255,255,255,0.97), rgba(245,249,255,0.98));
        border: 1px solid rgba(15, 23, 36, 0.05);
        box-shadow: 0 8px 26px rgba(11,35,60,0.05);
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
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.02em;
    }

    .revenue-panel-sub {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .revenue-chart-wrap {
        position: relative;
        height: 260px;
        width: 100%;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.02em;
    }

    .link-lihat {
        font-size: 12px;
        font-weight: 700;
        color: var(--blue);
        text-decoration: none;
        transition: opacity 0.2s ease;
    }

    .link-lihat:hover {
        opacity: 0.75;
    }

    .table-card {
        background: rgba(255,255,255,0.96);
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(15,23,36,0.05);
        box-shadow: 0 10px 30px rgba(11,35,60,0.04);
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }

    .data-table thead tr {
        background: #f8fbff;
        border-bottom: 1px solid #edf3f9;
    }

    .data-table th,
    .data-table td {
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
    }

    .data-table th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
    }

    .data-table td {
        font-size: 13px;
        color: var(--text);
    }

    .data-table tbody tr:hover {
        background: #fafcff;
    }

    .td-date {
        color: var(--text-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .td-bold {
        font-weight: 700;
    }

    .td-money {
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .badge-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 999px;
        display: block;
        background: currentColor;
    }

    .badge-status.lunas {
        background: var(--green-light);
        color: var(--green);
    }

    .badge-status.belum_lunas {
        background: var(--red-light);
        color: var(--red);
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 40px;
        margin-bottom: 12px;
        opacity: 0.38;
    }

    .mobile-transaction-list {
        display: none;
    }

    @media (max-width: 1100px) {
        .stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 639px) {
        .content-inner {
            padding-left: 0;
            padding-right: 0;
        }

        .welcome-strip {
            padding: 16px 14px 14px;
            border-radius: 18px;
        }

        .welcome-top {
            align-items: flex-start;
        }

        .welcome-meta {
            gap: 8px;
        }

        .welcome-meta .meta-pill {
            width: 100%;
            justify-content: center;
        }

        .filters-panel {
            padding: 10px 12px;
            border-radius: 16px;
        }

        .filters-row {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }

        .filter-select {
            width: 100%;
            min-width: 0;
            padding: 9px 12px;
        }

        .stat-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stat-card {
            min-height: 138px;
            padding: 16px 16px 14px;
        }

        .stat-value {
            font-size: 28px;
        }

        .stat-value.tiny {
            font-size: 22px;
        }

        .penjualan-value {
            font-size: 24px;
        }

        .chart-area {
            height: 70px;
        }
    }
</style>
@endpush

@section('content')
<div class="content-inner">
    <div class="welcome-strip">
        <div class="welcome-top">
            <div>
                <div class="welcome-title">Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</div>
                <div class="welcome-sub">Kelola aktivitas pencatatan transaksi dengan mudah.</div>
            </div>
        </div>

        <div class="welcome-meta">
            <span class="meta-pill">
                <i class="fas fa-user" style="font-size:11px;"></i>
                {{ Auth::user()->role === 'admin' ? 'Admin' : 'Kepala Cabang' }}
            </span>
            <span class="meta-pill">
                <i class="fas fa-building" style="font-size:11px;"></i>
                {{ $cabang === 'semua' ? 'Seluruh Cabang' : $cabang }}
            </span>
            <span class="meta-pill">
                <i class="fas fa-clock" style="font-size:11px;"></i>
                {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB
            </span>
        </div>
    </div>

    <div class="filters-panel">
        <form method="GET" action="{{ route('dashboard') }}" class="filters-row" id="filterForm">
            <label class="filter-select">
                <i class="fas fa-calendar-alt"></i>
                <select name="bulan" onchange="document.getElementById('filterForm').submit()">
                    <option value="ini" {{ $bulan === 'ini' ? 'selected' : '' }}>Bulan Ini</option>
                    @for($m = 0; $m < 12; $m++)
                        @php $mo = now()->subMonths($m)->format('Y-m'); @endphp
                        <option value="{{ $mo }}" {{ $bulan === $mo ? 'selected' : '' }}>
                            {{ now()->subMonths($m)->isoFormat('MMMM YYYY') }}
                        </option>
                    @endfor
                </select>
                <i class="fas fa-chevron-down chevron" style="font-size:11px;"></i>
            </label>

            @if(Auth::user()->role === 'admin')
            <label class="filter-select">
                <i class="fas fa-map-marker-alt"></i>
                <select name="cabang" onchange="document.getElementById('filterForm').submit()">
                    <option value="semua" {{ $cabang === 'semua' ? 'selected' : '' }}>Semua Cabang</option>
                    @foreach($daftarCabang as $c)
                        <option value="{{ $c }}" {{ $cabang === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down chevron" style="font-size:11px;"></i>
            </label>
            @endif
        </form>
    </div>

    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-icon-wrap blue">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value blue">{{ $transaksiHariIni }}</div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon-wrap red">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-label">Belum Lunas</div>
            <div class="stat-value red">{{ $belumLunas }}</div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon-wrap green">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-label">Total Penjualan Hari Ini</div>
            <div class="stat-value green tiny">Rp{{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</div>
        </div>

        <div class="stat-card teal chart-card">
            <div class="stat-card-header">
                <div class="stat-label">Penjualan Bulan Ini</div>
                <div class="penjualan-value">Rp{{ number_format($penjualanBulanIni, 0, ',', '.') }}</div>
                @if($persenPerubahan > 0)
                    <span class="growth-badge up">
                        <i class="fas fa-arrow-trend-up"></i>
                        {{ $persenPerubahan }}% dibanding bulan lalu
                    </span>
                @elseif($persenPerubahan < 0)
                    <span class="growth-badge down">
                        <i class="fas fa-arrow-trend-down"></i>
                        {{ abs($persenPerubahan) }}% dibanding bulan lalu
                    </span>
                @else
                    <span class="growth-badge flat">— Sama dengan bulan lalu</span>
                @endif
            </div>
            <div class="chart-area">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="revenue-panel">
        <div class="revenue-panel-header">
            <div>
                <div class="revenue-panel-title">Grafik Pendapatan</div>
                <div class="revenue-panel-sub">Perkembangan pendapatan 7 hari terakhir</div>
            </div>
            <div class="growth-badge up">
                <i class="fas fa-arrow-trend-up"></i>
                Rp{{ number_format(array_sum(array_column($chartData, 'total')), 0, ',', '.') }} total
            </div>
        </div>
        <div class="revenue-chart-wrap">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="section-header">
        <div class="section-title">Transaksi Terbaru</div>
        <a href="#" class="link-lihat">Lihat Semua</a>
    </div>

    <div class="table-card desktop-table">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pembeli / Tempat</th>
                        <th>Jumlah Ikan (Kg)</th>
                        <th>Total (Rp)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $trx)
                    <tr>
                        <td class="td-date">
                            {{ $trx->tanggal_transaksi->format('d M Y') }},<br>
                            {{ $trx->tanggal_transaksi->format('H:i') }}
                        </td>
                        <td class="td-bold">{{ $trx->pembeli_nama }}</td>
                        <td>{{ number_format($trx->jumlah_kg, 0, ',', '.') }}</td>
                        <td class="td-money">{{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge-status {{ $trx->status }}">
                                {{ $trx->status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div><i class="fas fa-inbox"></i></div>
                                <div>Belum ada transaksi.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mobile-transaction-list">
        @forelse($transaksiTerbaru as $trx)
            <article class="txn-item">
                <div class="txn-head">
                    <div class="txn-name">{{ $trx->pembeli_nama }}</div>
                    <div class="txn-total">Rp{{ number_format($trx->total, 0, ',', '.') }}</div>
                </div>
                <div class="txn-meta">
                    <span>{{ $trx->tanggal_transaksi->format('d M Y') }}</span>
                    <span>{{ number_format($trx->jumlah_kg, 0, ',', '.') }} Kg</span>
                </div>
                <div class="txn-foot">
                    <span class="txn-amount">{{ $trx->status === 'lunas' ? 'Lunas' : 'Belum lunas' }}</span>
                    <span class="badge-status {{ $trx->status }}">
                        {{ $trx->status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                    </span>
                </div>
            </article>
        @empty
            <div class="empty-state" style="padding: 32px 16px;">
                <div><i class="fas fa-inbox"></i></div>
                <div>Belum ada transaksi.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chartLabels = @json(array_column($chartData, 'label'));
    const chartValues = @json(array_column($chartData, 'total'));

    if (document.getElementById('salesChart')) {
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesGradient = salesCtx.createLinearGradient(0, 0, 0, 120);
        salesGradient.addColorStop(0, 'rgba(30,136,229,0.28)');
        salesGradient.addColorStop(1, 'rgba(30,136,229,0.02)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartValues,
                    borderColor: '#1e88e5',
                    backgroundColor: salesGradient,
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointBackgroundColor: '#1e88e5',
                    pointBorderWidth: 0,
                    tension: 0.35,
                    fill: true,
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
                    x: { display: false },
                    y: { display: false }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        });
    }

    if (document.getElementById('revenueChart')) {
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan',
                    data: chartValues,
                    borderRadius: 8,
                    backgroundColor: 'rgba(30,136,229,0.75)',
                    borderColor: '#1e88e5',
                    borderWidth: 1,
                    barThickness: 18,
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
                            callback: value => 'Rp' + Number(value).toLocaleString('id-ID'),
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
