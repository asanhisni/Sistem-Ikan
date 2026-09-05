<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            touch-action: manipulation;
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        /* Cegah auto-zoom pada iOS input focus */
        @media (max-width: 768px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }

        /* ─── GLOBAL LOADER INDIKATOR (FLIP LOGO 3D + BLUR) ─── */
        .app-loader-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.4s;
        }

        .app-loader-overlay.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .app-flip-logo-box {
            width: 86px;
            height: 86px;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1000px;
        }

        .app-flip-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            animation: appLogo3DFlip 1.8s ease-in-out infinite;
            filter: drop-shadow(0 10px 20px rgba(2, 62, 138, 0.25));
        }

        @keyframes appLogo3DFlip {
            0% {
                transform: rotateY(0deg) scale(1);
            }
            50% {
                transform: rotateY(180deg) scale(1.12);
            }
            100% {
                transform: rotateY(360deg) scale(1);
            }
        }

        .app-loader-label {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #03045e;
            text-transform: uppercase;
            animation: appPulseText 1.4s ease-in-out infinite alternate;
        }

        @keyframes appPulseText {
            0% { opacity: 0.6; transform: translateY(0); }
            100% { opacity: 1; transform: translateY(-2px); }
        }

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
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            background: rgba(255,255,255,0.06);
            flex-shrink: 0;
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
            background: rgba(13, 27, 42, 0.88);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0;
            z-index: 50;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
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
            position: relative;
            user-select: none;
            padding: 4px 6px;
            border-radius: 12px;
            transition: background 0.15s ease;
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e88e5, #42a5f5);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; color: #fff; font-weight: 700;
            box-shadow: 0 4px 12px rgba(30,136,229,0.25);
            transition: transform 0.15s ease;
        }

        .user-info:hover .avatar {
            transform: scale(1.05);
        }

        .user-details { text-align: right; }
        .user-name    { font-size: 14px; font-weight: 700; color: #ffffff; }
        .user-role    { font-size: 11px; color: rgba(255, 255, 255, 0.65); }

        /* ─── DROPDOWN MENU USER PROFILE ─── */
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 220px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            padding: 8px;
            display: none;
            z-index: 9999;
            animation: dropdownFade 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .user-dropdown-menu.show {
            display: block;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .user-dropdown-header {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 6px;
        }

        .user-dropdown-header .dropdown-name {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
        }

        .user-dropdown-header .dropdown-role {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border: none;
            background: none;
            border-radius: 10px;
            color: #e53935;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s ease;
        }

        .user-dropdown-item:hover {
            background: #fef2f2;
            color: #dc2626;
        }

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
            color: #ffffff;
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
                background: rgba(13, 27, 42, 0.92);
            }
            .topbar-title {
                font-size: 15px;
                margin-left: 4px;
                color: #ffffff;
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
        }

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

<!-- GLOBAL LOADER INDIKATOR (Semua Halaman) -->
<div class="app-loader-overlay" id="appGlobalLoader">
    <div class="app-flip-logo-box">
        <img src="{{ asset('logo/logo_ikan.png') }}" alt="Loading..." class="app-flip-logo-img" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3050/3050247.png'">
    </div>
    <div class="app-loader-label">Memuat Sistem...</div>
</div>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ══════════════ SIDEBAR ══════════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-top">
            <div class="brand-icon">
                <img src="{{ asset('logo/logo_ikan.png') }}" alt="Logo" onerror="this.parentElement.innerHTML='🐟'">
            </div>
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
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            Kepala Cabang
        </a>
        @endif

        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            Laporan
        </a>
    </nav>
</aside>

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
            <div class="user-info" id="userProfileToggle" onclick="toggleUserDropdown(event)">
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Kepala Cabang' }}</div>
                </div>
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <i class="fas fa-chevron-down" style="font-size: 11px; color: rgba(255,255,255,0.7); margin-left: 2px;"></i>

                <!-- DROPDOWN CONTENT -->
                <div class="user-dropdown-menu" id="userDropdownMenu" onclick="event.stopPropagation()">
                    <div class="user-dropdown-header">
                        <div class="dropdown-name">{{ Auth::user()->name }}</div>
                        <div class="dropdown-role">{{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->cabangRelasi ? Auth::user()->cabangRelasi->nama_cabang : (Auth::user()->cabang ?? 'Kepala Cabang')) }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="user-dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Keluar dari Sistem</span>
                        </button>
                    </form>
                </div>
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
        if (sidebar) sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
    }

    // Toggle Dropdown User Topbar
    function toggleUserDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('userDropdownMenu');
        if (menu) {
            menu.classList.toggle('show');
        }
    }

    // Tutup Dropdown jika klik di luar
    window.addEventListener('click', function(e) {
        const menu = document.getElementById('userDropdownMenu');
        const toggle = document.getElementById('userProfileToggle');
        if (menu && menu.classList.contains('show') && toggle && !toggle.contains(e.target)) {
            menu.classList.remove('show');
        }
    });

    // Auto-dismiss Loader di semua halaman saat dokumen selesai dimuat
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loader = document.getElementById('appGlobalLoader');
            if (loader) {
                loader.classList.add('fade-out');
                setTimeout(() => loader.remove(), 500);
            }
        }, 300);
    });

    // ─── DISABLE ZOOM PADA MOBILE / SMARTPHONE ───
    // Cegah multi-touch pinch-to-zoom di iOS Safari & browser mobile
    document.addEventListener('touchstart', function(e) {
        if (e.touches && e.touches.length > 1) {
            e.preventDefault();
        }
    }, { passive: false });

    // Cegah gesture pinch zoom spesifik Safari iOS
    document.addEventListener('gesturestart', function(e) {
        e.preventDefault();
    });
    document.addEventListener('gesturechange', function(e) {
        e.preventDefault();
    });
    document.addEventListener('gestureend', function(e) {
        e.preventDefault();
    });
</script>
@stack('scripts')
</body>
</html>