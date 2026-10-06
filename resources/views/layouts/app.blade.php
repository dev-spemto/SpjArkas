<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPJ ARKAS - Administrasi BOSP Sekolah</title>
    
    <!-- Framework CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-width: 270px;
            --sidebar-bg: #0e4d2a;
            --sidebar-bg-hover: rgba(255, 255, 255, 0.15);
        }

        /* Reset Total Margin & Padding */
        html, body {
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            height: 100vh;
            position: sticky;
            top: 0;
            background: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            flex-shrink: 0;
        }

        .sidebar-brand-title {
            font-size: 1.15rem;
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        .sidebar-brand-subtitle {
            font-size: 0.75rem;
            margin: 0;
            opacity: 0.75;
        }

        /* Area Menu Sidebar Scrollable */
        .sidebar-menu {
            padding: 0.75rem 0.85rem;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .menu-section {
            font-size: 0.65rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.8px;
            padding: 0.8rem 0.75rem 0.25rem 0.75rem;
            text-transform: uppercase;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 0.55rem 0.85rem;
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.15rem;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover, 
        .nav-link-custom.active {
            background-color: var(--sidebar-bg-hover);
            color: #ffffff !important;
        }

        /* Main Content & Layout Body */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
            background-color: var(--bs-body-bg);
        }

        .top-header {
            padding: 1rem 1.75rem;
            background-color: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .header-title-box {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-sidebar-btn {
            background: transparent;
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
            color: var(--bs-body-color);
        }

        .header-app-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .header-app-desc {
            font-size: 0.8rem;
            margin: 0;
            color: var(--bs-secondary-color);
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #198754;
            background: rgba(25, 135, 84, 0.12);
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            white-space: nowrap;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #198754;
            border-radius: 50%;
            display: inline-block;
        }

        .content-body {
            padding: 1.5rem 1.75rem;
            flex: 1;
        }

        /* Footer Bawah Mendatar (Horizontal Bottom Footer) */
        .app-footer {
            padding: 1rem 1.75rem;
            background-color: var(--bs-body-bg);
            border-top: 1px solid var(--bs-border-color);
            font-size: 0.8rem;
            color: var(--bs-secondary-color);
        }

        /* --- STYLING DARK MODE ADEM & KONTRAS TINGGI --- */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121417;
            --bs-body-color: #e2e8f0;
            --bs-border-color: rgba(255, 255, 255, 0.15);
        }

        [data-bs-theme="dark"] .sidebar {
            background: #082816 !important;
        }

        [data-bs-theme="dark"] .card {
            background-color: #181b20 !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        [data-bs-theme="dark"] .top-header,
        [data-bs-theme="dark"] .app-footer {
            background-color: #181b20 !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* Tabel Dark Mode */
        [data-bs-theme="dark"] .table {
            --bs-table-bg: #181b20;
            --bs-table-color: #e2e8f0;
            --bs-table-border-color: rgba(255, 255, 255, 0.12);
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .table thead th, 
        [data-bs-theme="dark"] .table tfoot td,
        [data-bs-theme="dark"] .table-light,
        [data-bs-theme="dark"] .card-header,
        [data-bs-theme="dark"] .card-footer,
        [data-bs-theme="dark"] .bg-white {
            background-color: #1f2329 !important;
            color: #f1f5f9 !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* Sel Tabel Terang Kontras */
        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] .table td span,
        [data-bs-theme="dark"] .table td div,
        [data-bs-theme="dark"] .table td small {
            color: #cbd5e1 !important;
        }

        /* Timpa Inline Style Warna Abu-abu Tua */
        [data-bs-theme="dark"] .table td[style*="color"],
        [data-bs-theme="dark"] .table td span[style*="color"],
        [data-bs-theme="dark"] .text-muted,
        [data-bs-theme="dark"] .text-secondary,
        [data-bs-theme="dark"] .text-body-secondary {
            color: #cbd5e1 !important;
        }

        /* Aksen Warna Transaksi */
        [data-bs-theme="dark"] .text-success, 
        [data-bs-theme="dark"] .table td.text-success, 
        [data-bs-theme="dark"] .table td .text-success { color: #4ade80 !important; }

        [data-bs-theme="dark"] .text-danger, 
        [data-bs-theme="dark"] .table td.text-danger, 
        [data-bs-theme="dark"] .table td .text-danger { color: #f87171 !important; }

        [data-bs-theme="dark"] .text-primary, 
        [data-bs-theme="dark"] .table td.text-primary, 
        [data-bs-theme="dark"] .table td .text-primary { color: #60a5fa !important; }

        [data-bs-theme="dark"] .text-warning, 
        [data-bs-theme="dark"] .table td.text-warning, 
        [data-bs-theme="dark"] .table td .text-warning { color: #facc15 !important; }

        [data-bs-theme="dark"] .text-info, 
        [data-bs-theme="dark"] .table td.text-info, 
        [data-bs-theme="dark"] .table td .text-info { color: #38bdf8 !important; }

        [data-bs-theme="dark"] .btn-outline-primary { border-color: #3b82f6; color: #60a5fa; }
        [data-bs-theme="dark"] .btn-outline-primary:hover { background-color: #3b82f6; color: #fff; }

        [data-bs-theme="dark"] .btn-outline-warning { border-color: #eab308; color: #facc15; }
        [data-bs-theme="dark"] .btn-outline-warning:hover { background-color: #eab308; color: #000; }

        [data-bs-theme="dark"] .btn-outline-success { border-color: #22c55e; color: #4ade80; }
        [data-bs-theme="dark"] .btn-outline-success:hover { background-color: #22c55e; color: #fff; }

        [data-bs-theme="dark"] .btn-outline-info { border-color: #06b6d4; color: #38bdf8; }
        [data-bs-theme="dark"] .btn-outline-info:hover { background-color: #06b6d4; color: #fff; }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Brand Logo & Header -->
        <div class="sidebar-brand">
            <i class="bi bi-journal-check fs-2 text-white"></i>
            <div>
                <p class="sidebar-brand-title">SPJ ARKAS</p>
                <p class="sidebar-brand-subtitle">Administrasi BOSP Sekolah</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-menu">
            <!-- 1. MENU UTAMA -->
            <div class="menu-section">MENU UTAMA</div>

            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2 me-2 fs-6"></i>
                <span>Dashboard Home</span>
            </a>

            <a href="{{ route('school-profile.index') }}" class="nav-link-custom {{ request()->routeIs('school-profile.*') ? 'active' : '' }}">
                <i class="bi bi-building me-2 fs-6"></i>
                <span>Profil Sekolah</span>
            </a>

            <!-- 2. DOKUMEN & LAPORAN -->
            <div class="menu-section">DOKUMEN & LAPORAN</div>

            @php
                $isSpjActive = request()->routeIs('spjs.*');
                $currentJenisBos = request()->query('jenis_bos', 'Reguler');
            @endphp

            <!-- Dropdown Penganggaran BKU/SPJ -->
            <a class="nav-link-custom d-flex align-items-center justify-content-between text-decoration-none {{ $isSpjActive ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#submenuSpj" 
               role="button" 
               aria-expanded="{{ $isSpjActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="bi bi-receipt me-2 fs-6"></i>
                    <span>Penganggaran BKU/SPJ</span>
                </div>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isSpjActive ? 'show' : '' }} ps-3 my-1" id="submenuSpj">
                <a href="{{ route('spjs.index', ['jenis_bos' => 'Reguler']) }}" 
                   class="nav-link-custom py-1 {{ $isSpjActive && $currentJenisBos == 'Reguler' ? 'fw-bold text-white' : 'text-white-50' }}" style="font-size: 0.85rem;">
                    <i class="bi bi-dot fs-4 me-1"></i>
                    <span>BOS Reguler</span>
                </a>
                <a href="{{ route('spjs.index', ['jenis_bos' => 'Afirmasi']) }}" 
                   class="nav-link-custom py-1 {{ $isSpjActive && $currentJenisBos == 'Afirmasi' ? 'fw-bold text-white' : 'text-white-50' }}" style="font-size: 0.85rem;">
                    <i class="bi bi-dot fs-4 me-1"></i>
                    <span>BOS Afirmasi</span>
                </a>
                <a href="{{ route('spjs.index', ['jenis_bos' => 'Kinerja']) }}" 
                   class="nav-link-custom py-1 {{ $isSpjActive && $currentJenisBos == 'Kinerja' ? 'fw-bold text-white' : 'text-white-50' }}" style="font-size: 0.85rem;">
                    <i class="bi bi-dot fs-4 me-1"></i>
                    <span>BOS Kinerja</span>
                </a>
                <a href="{{ route('spjs.index', ['jenis_bos' => 'Daerah']) }}" 
                   class="nav-link-custom py-1 {{ $isSpjActive && $currentJenisBos == 'Daerah' ? 'fw-bold text-white' : 'text-white-50' }}" style="font-size: 0.85rem;">
                    <i class="bi bi-dot fs-4 me-1"></i>
                    <span>BOS Daerah</span>
                </a>
            </div>

            <!-- Pembukuan BOSP -->
            <a href="{{ route('bosp-documents.index') }}" class="nav-link-custom {{ request()->routeIs('bosp-documents.*') ? 'active' : '' }}">
                <i class="bi bi-folder-symlink me-2 fs-6"></i>
                <span>Pembukuan BOSP</span>
            </a>

            <!-- Download Template -->
            <a href="{{ route('templates.index') }}" class="nav-link-custom {{ request()->routeIs('templates.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-arrow-down me-2 fs-6"></i>
                <span>Download Template</span>
            </a>

            <!-- 3. MASTER DATA -->
            <div class="menu-section">MASTER DATA</div>

            @php
                $isMasterActive = request()->routeIs('account-codes.*') || request()->routeIs('activity-codes.*');
            @endphp

            <a class="nav-link-custom d-flex align-items-center justify-content-between text-decoration-none {{ $isMasterActive ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#submenuMasterData" 
               role="button" 
               aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="bi bi-database me-2 fs-6"></i>
                    <span>Master Data</span>
                </div>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isMasterActive ? 'show' : '' }} ps-3 my-1" id="submenuMasterData">
                <a href="{{ route('account-codes.index') }}" class="nav-link-custom py-1 {{ request()->routeIs('account-codes.*') ? 'fw-bold text-white' : 'text-white-50' }}" style="font-size: 0.85rem;">
                    <i class="bi bi-hash fs-6 me-2"></i>
                    <span>Kode Rekening</span>
                </a>
                <a href="{{ route('activity-codes.index') }}" class="nav-link-custom py-1 {{ request()->routeIs('activity-codes.*') ? 'fw-bold text-white' : 'text-white-50' }}" style="font-size: 0.85rem;">
                    <i class="bi bi-list-task fs-6 me-2"></i>
                    <span>Kode Kegiatan</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-title-box">
                <button class="toggle-sidebar-btn" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h1 class="header-app-name">Sistem Informasi SPJ & ARKAS</h1>
                    <p class="header-app-desc">Pencatatan Transaksi, Rekap BKP & Generator Kwitansi Otomatis</p>
                </div>
            </div>

            <!-- Dark Mode Switch & Status Badge -->
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-2" id="btnThemeToggle" title="Ganti Mode Tampilan">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    <span class="small fw-semibold" id="themeText">Dark Mode</span>
                </button>

                <div class="status-badge">
                    <span class="status-dot"></span>
                    Sistem Aktif
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Horizontal Bottom Footer (Persis Mode Bel Sekolah) -->
        <footer class="app-footer mt-auto">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>
                    <span class="fw-bold text-body">&copy; {{ date('Y') }} Tim IT SMP Muhammadiyah Tonjong.</span>
                    <small class="d-block text-muted">Sistem Informasi SPJ & ARKAS — Brebes, Jawa Tengah.</small>
                </div>
                <div class="d-flex align-items-center gap-3 text-muted small flex-wrap">
                    <span><i class="bi bi-envelope me-1"></i>smpmuhitonjong@gmail.com</span>
                    <span><i class="bi bi-whatsapp me-1"></i>(+62) 851-850-333-77</span>
                    <a href="https://smpmuhtonjong.sch.id" target="_blank" class="text-muted text-decoration-none">
                        <i class="bi bi-globe me-1"></i>smpmuhtonjong.sch.id
                    </a>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'flex';
            } else {
                sidebar.style.display = 'none';
            }
        });
    }

    const htmlElement = document.documentElement;
    const btnThemeToggle = document.getElementById('btnThemeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');

    function setTheme(theme) {
        htmlElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);

        if (theme === 'dark') {
            themeIcon.className = 'bi bi-sun-fill text-warning';
            themeText.textContent = 'Light Mode';
            btnThemeToggle.classList.remove('btn-outline-secondary');
            btnThemeToggle.classList.add('btn-outline-warning');
        } else {
            themeIcon.className = 'bi bi-moon-stars-fill text-dark';
            themeText.textContent = 'Dark Mode';
            btnThemeToggle.classList.remove('btn-outline-warning');
            btnThemeToggle.classList.add('btn-outline-secondary');
        }
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    if (btnThemeToggle) {
        btnThemeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }
</script>
@stack('scripts')
</body>
</html>