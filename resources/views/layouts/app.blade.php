<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Sistem Ikan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-w:    240px;
            --sidebar-bg:   #0d1b2a;
            --sidebar-text: rgba(255,255,255,0.65);
            --sidebar-active: #1e88e5;
            --topbar-h:     64px;
            --white:        #ffffff;
            --bg:           linear-gradient(180deg,#f6f8fb,#f4f6fb);
            --card:         #ffffff;
            --border:       #e8ecf0;
            --text:         #0f1724;
            --text-muted:   #6b7280;
            --accent:       #2b9cff;
            --blue:         #1e88e5;
            --blue-light:   #e3f2fd;
            --red:          #e53935;
            --red-light:    #ffebee;
            --green:        #43a047;
            --green-light:  #e8f5e9;
            --shadow:       0 10px 30px rgba(11,35,60,0.12);
            --elev:         0 6px 24px rgba(11,35,60,0.08);
            --radius:       18px;
            --glass:        rgba(255,255,255,0.6);
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }

        /* ══════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            flex-shrink: 0;
        }

        .brand-top {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #1e88e5, #42a5f5);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .brand-desc {
            font-size: 11px;
            color: var(--sidebar-text);
            line-height: 1.4;
            padding-left: 46px;
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.3);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 20px 20px 8px;
            flex-shrink: 0;
        }

        .sidebar-nav { 
            flex: 1; 
            padding: 8px 12px; 
            overflow-y: auto; 
        }

        .nav-item { 
            display:flex; 
            align-items:center; 
            gap:12px; 
            padding:12px 14px; 
            border-radius:10px; 
            color:var(--sidebar-text); 
            text-decoration:none; 
            font-size:14px; 
            font-weight:600; 
            margin-bottom:6px; 
            transition:all 0.12s ease; 
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 15px;
            transition: transform 0.2s;
        }

        .nav-item:hover { background: rgba(255,255,255,0.03); color:#fff }
        .nav-item:hover i { transform: translateY(-1px) }

        .nav-item.active { 
            background: linear-gradient(90deg,var(--sidebar-active),#1e88e5); 
            color:#fff; 
            font-weight:700; 
            box-shadow: 0 6px 20px rgba(30,136,229,0.12) 
        }

        .sidebar-footer { 
            padding: 16px 12px; 
            border-top: 1px solid rgba(255,255,255,0.07);
            margin-top: auto;
            flex-shrink: 0;
        }

        .logout-btn { 
            display:flex; 
            align-items:center; 
            gap:10px; 
            width:100%; 
            padding:11px 14px; 
            border-radius:10px; 
            background: rgba(255,255,255,0.03); 
            border: 1px solid rgba(255,255,255,0.06); 
            color: var(--sidebar-text); 
            font-size:13px; 
            font-weight:600; 
            cursor:pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover { 
            background: rgba(229,57,53,0.15); 
            color: #ff8a80; 
            border-color: rgba(229,57,53,0.25);
        }

        /* ══════════════════════════════════════════
           MAIN LAYOUT
        ══════════════════════════════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ══════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════ */
        .topbar {
            height: var(--topbar-h);
            background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(250,252,255,0.8));
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0;
            z-index: 50;
            box-shadow: 0 6px 20px rgba(11,35,60,0.04);
            backdrop-filter: blur(6px);
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e88e5, #42a5f5);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; color: #fff; font-weight: 700;
        }

        .user-details { text-align: right; }
        .user-name    { font-size: 14px; font-weight: 600; color: var(--text); }
        .user-role    { font-size: 11px; color: var(--text-muted); }

        /* ══════════════════════════════════════════
           PAGE CONTENT
        ══════════════════════════════════════════ */
        .page-content {
            padding: 22px;
            flex: 1;
            min-width: 0;
        }

        /* ══════════════════════════════════════════
           HAMBURGER & MOBILE NAVIGATION
        ══════════════════════════════════════════ */
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text);
            cursor: pointer;
            padding: 6px;
            line-height: 1;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2,6,23,0.45);
            z-index: 1200;
            backdrop-filter: blur(3px);
        }

        .bottom-nav {
            display: none;
        }

        .fab-create {
            display: none;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.open {
                display: block;
            }
            .main-wrap {
                margin-left: 0;
            }
            .hamburger {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .page-content {
                padding: 16px 14px 92px;
            }
            .topbar {
                padding: 0 14px;
                height: 58px;
            }
            .topbar-title {
                font-size: 15px;
                margin-left: 4px;
            }
            .topbar-right {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .user-details {
                display: none;
            }
            .avatar {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            /* --- PERBAIKAN Z-INDEX & PADDING SIDEBAR MOBILE --- */
            .sidebar {
                width: min(82vw, 260px);
                transform: translateX(-105%);
                box-shadow: 0 20px 40px rgba(2,6,23,0.18);
                z-index: 1300; /* Naikkan layer di atas bottom-nav */
                padding-bottom: 70px; /* Jarak aman tombol logout */
            }
            .sidebar.open {
                transform: translateX(0);
            }

            /* --- PERBAIKAN BOTTOM NAV & FAB --- */
            .bottom-nav {
                display: flex;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255,255,255,0.97);
                backdrop-filter: blur(8px);
                border-top: 1px solid rgba(15,23,36,0.06);
                box-shadow: 0 -10px 24px rgba(15,23,36,0.08);
                z-index: 1100;
                justify-content: space-around;
                align-items: stretch;
                padding: 8px 6px max(10px, env(safe-area-inset-bottom));
                gap: 2px;
            }
            .b-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                color: #8a96a5;
                text-decoration: none;
                font-size: 10px;
                font-weight: 700;
                flex: 1;
                min-width: 0;
                padding: 6px 4px 7px;
                border-radius: 12px;
                transition: all 0.2s ease;
            }
            .b-nav-item i {
                font-size: 17px;
                line-height: 1;
            }
            .b-nav-item.active {
                color: var(--blue);
                background: rgba(30,136,229,0.08);
            }
            .b-nav-item.active i {
                transform: scale(1.04);
            }

            .fab-create {
                position: fixed;
                right: 18px;
                bottom: 82px;
                z-index: 1000;
            }
            .fab-create .btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 54px;
                height: 54px;
                border-radius: 50%;
                box-shadow: 0 10px 28px rgba(30,136,229,0.28);
                background: var(--blue);
                color: #fff;
                border: none;
                font-size: 24px;
                font-weight: 700;
                text-decoration: none;
            }
            body.page-transaksi .fab-create {
                display: block;
            }
        }

        body.page-transaksi .fab-create { display:block; }

        .card, .stat-card, .table-card, .tabel-sederhana, .pos-card {
            border-radius: var(--radius);
            box-shadow: var(--elev);
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,252,255,0.98));
            border: 1px solid rgba(11,35,60,0.04);
        }

        .btn-tambah { border-radius: 12px; box-shadow: var(--elev); }
        .btn-aksi { box-shadow: 0 6px 14px rgba(11,35,60,0.06); }

        .filter-select, .cari-box input, .cari-box select, .input-large {
            background: #fff; border:1px solid rgba(11,35,60,0.05); box-shadow: 0 6px 18px rgba(11,35,60,0.03);
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ══════════════ SIDEBAR ══════════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-top">
            <div class="brand-icon">🐟</div>
            <span class="brand-name">SAMI JAYA</span>
        </div>
        <div class="brand-desc">Sistem Pencatatan &amp; Monitoring Transaksi</div>
    </div>

    <div class="sidebar-section-label">Menu Utama</div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            Dashboard
        </a>
        <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i>
            Pesanan/Transaksi
        </a>

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('pelanggan.index') }}" class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            Pelanggan
        </a>
        <a href="{{ route('ikan.index') }}" class="nav-item {{ request()->routeIs('ikan.*') ? 'active' : '' }}">
            <i class="fas fa-fish"></i>
            Data Ikan
        </a>
        @endif

        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            Laporan
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar dari Sistem</span>
            </button>
        </form>
    </div>
</aside>

<!-- Floating Action Button (di luar sidebar) -->
<div class="fab-create" aria-hidden="false">
    <a href="{{ route('transaksi.create') }}" class="btn" title="Buat Transaksi Baru">+</a>
</div>

<!-- ══════════════ MAIN ══════════════ -->
<div class="main-wrap">
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="hamburger" onclick="toggleSidebar()" aria-label="Buka menu">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="topbar-right">
            <div class="user-info">
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Kepala Cabang' }}</div>
                </div>
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            </div>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<!-- ══════════════ BOTTOM NAV (MOBILE) ══════════════ -->
<nav class="bottom-nav" aria-label="Navigasi mobile">
    <a href="{{ route('dashboard') }}" class="b-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('transaksi.index') }}" class="b-nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
        <i class="fas fa-receipt"></i>
        <span>Transaksi</span>
    </a>

    @if(Auth::user()->role === 'admin')
    <a href="{{ route('pelanggan.index') }}" class="b-nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Pelanggan</span>
    </a>
    <a href="{{ route('ikan.index') }}" class="b-nav-item {{ request()->routeIs('ikan.*') ? 'active' : '' }}">
        <i class="fas fa-fish"></i>
        <span>Ikan</span>
    </a>
    @endif

    <a href="{{ route('laporan.index') }}" class="b-nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>Laporan</span>
    </a>
</nav>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (!sidebar || !overlay) return;
        const isOpen = sidebar.classList.contains('open');
        sidebar.classList.toggle('open', !isOpen);
        overlay.classList.toggle('open', !isOpen);
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }
</script>

@stack('scripts')
</body>
</html>