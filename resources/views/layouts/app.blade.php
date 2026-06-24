<!DOCTYPE html>
<html lang="en" data-theme="light"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">

    @stack('styles')
    <style>
        :root {
            /* Light Theme (Default) */
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #3f37c9;
            --accent-color: #17a2b8;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --text-color: #2b2d42;
            --text-light: #8d99ae;
            --light-bg: #f8f9fa;
            --white-bg: #ffffff;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --border-color: #e9ecef;
            --header-height: 75px;
            --footer-height: 60px;
            --sidebar-width-expanded: 260px;
            --sidebar-width-collapsed: 80px;
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 4px 15px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        /* Dark Theme Overrides */
        [data-theme="dark"] {
            --light-bg: #0f172a;        /* Background utama lebih gelap */
            --white-bg: #1e293b;       /* Background header/card */
            --text-color: #f1f5f9;     /* Warna teks terang */
            --text-light: #94a3b8;     /* Warna teks sekunder */
            --border-color: #334155;
            --sidebar-bg: #020617;     /* Sidebar lebih gelap di mode dark */
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width-expanded);
            background: var(--sidebar-bg);
            color: white;
            padding: 1.5rem 0;
            box-shadow: var(--shadow-medium);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            z-index: 1030;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        /* Hide scrollbar for Webkit browsers */
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: var(--sidebar-bg); }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 10px;
            border: 2px solid var(--sidebar-bg);
        }

        .sidebar { scrollbar-width: thin; scrollbar-color: var(--primary-color) var(--sidebar-bg); }

        body.sidebar-collapsed .sidebar { width: var(--sidebar-width-collapsed); }
        body.sidebar-collapsed .sidebar .sidebar-header h2 { display: none; }
        body.sidebar-collapsed .sidebar .sidebar-header { padding-bottom: 0; margin-bottom: 1rem; }
        body.sidebar-collapsed .sidebar .sidebar-nav ul li a span {
            opacity: 0; width: 0; height: 0; position: absolute; visibility: hidden;
        }
        body.sidebar-collapsed .sidebar .sidebar-nav ul li a { justify-content: center; padding: 0.75rem 0; }
        body.sidebar-collapsed .sidebar .sidebar-nav ul li a i { margin-right: 0; font-size: 1.3rem; }

        .sidebar-header {
            text-align: center;
            margin-bottom: 1rem;
            padding: 0 1rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            margin: 0; color: #ffffff; font-size: 1.25rem; font-weight: 600; padding-top: 0.5rem;
        }
        .sidebar-header h2 i { color: var(--accent-color); }

        .sidebar-nav { flex-grow: 1; padding: 0 0.5rem; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin-bottom: 0.5rem; }

        .sidebar ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: var(--transition);
            font-weight: 400;
            position: relative;
            overflow: hidden;
        }

        .sidebar ul li a i { margin-right: 0.85rem; font-size: 1.1rem; width: 24px; text-align: center; }

        .sidebar ul li a:hover,
        .sidebar ul li a.active:hover {
            background: var(--sidebar-hover); color: white; transform: translateX(3px);
        }

        .sidebar ul li a.active {
            background: var(--primary-color); color: white; font-weight: 500;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
        }

        body.sidebar-collapsed .sidebar ul li a::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-width-collapsed) + 5px);
            top: 50%;
            transform: translateY(-50%);
            background: #333;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            pointer-events: none;
            z-index: 1050;
        }

        body.sidebar-collapsed .sidebar ul li a:hover::after { opacity: 1; visibility: visible; }

        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin-left: var(--sidebar-width-expanded);
            transition: margin-left var(--transition);
            overflow: hidden;
        }

        body.sidebar-collapsed .main-wrapper { margin-left: var(--sidebar-width-collapsed); }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white-bg);
            padding: 0 2rem;
            box-shadow: var(--shadow-light);
            height: var(--header-height);
            flex-shrink: 0;
            z-index: 1020;
            transition: var(--transition);
        }

        .header-left { display: flex; align-items: center; }

        .sidebar-toggle, .theme-toggle {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            transition: var(--transition);
        }

        .sidebar-toggle:hover, .theme-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }

        /* Adjust hover color for dark mode */
        [data-theme="dark"] .sidebar-toggle:hover, 
        [data-theme="dark"] .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-toggle { margin-right: 1rem; }

        .header-title-static {
            font-weight: 600;
            margin: 0;
            font-size: 1.25rem;
            color: var(--primary-color);
        }

        .user-info { display: flex; align-items: center; gap: 0.75rem; }

        .user-info .user-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background-color: var(--primary-color); color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 1rem;
        }

        .user-info .user-details { display: flex; flex-direction: column; text-align: left; }
        .user-info .user-name { font-weight: 500; color: var(--text-color); font-size: 0.9rem; }
        .user-info .user-role { font-size: 0.75rem; color: var(--text-light); }

        .content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: var(--light-bg);
            transition: var(--transition);
        }

        .footer {
            height: var(--footer-height);
            text-align: center;
            padding: 1rem;
            background-color: var(--white-bg);
            color: var(--text-light);
            flex-shrink: 0;
            font-size: 0.875rem;
            border-top: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .footer p { margin: 0; }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); width: var(--sidebar-width-expanded); }
            body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
        }

        @media (max-width: 768px) {
            .header { padding: 0 1rem; height: 65px; }
            .header-title-static { font-size: 1.1rem; }
            .content-wrapper { padding: 1rem; }
            .user-info .user-details { display: none; }
        }

        @media (max-width: 576px) {
            .sidebar { width: 220px; }
            .sidebar.active { width: 220px; }
            .header-title-static { display: none; }
        }
    </style>
</head>

<body>
    @auth
        @if (Auth::user()->role === 'admin')
            @include('layouts.sidebar-admin')
        @elseif (Auth::user()->role === 'dosen')
            @include('layouts.sidebar-dosen')
        @elseif (Auth::user()->role === 'mahasiswa')
            @include('layouts.sidebar-mahasiswa')
        @endif
    @endauth

    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                @auth
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                @endauth
                <h3 class="header-title-static">Sistem Informasi Akademik Mahasiswa</h3>
            </div>

            <div class="user-info">
                <button class="theme-toggle" id="themeToggle" title="Ganti Tema">
                    <i class="fas fa-moon"></i>
                </button>
                
                @auth
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->profile_name ?? Auth::user()->name }}</span>
                        <span class="user-role text-end">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                    
                    {{-- BAGIAN YANG DIUBAH: Menambahkan logika foto profil --}}
                    <div class="user-avatar overflow-hidden">
                        @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto)
                            <img src="{{ asset('storage/' . Auth::user()->mahasiswa->foto) }}" 
                                alt="Profile" 
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                @endauth
            </div>
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Sistem Akademik - All rights reserved.</p>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Fitur Dark/Light Mode ---
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle.querySelector('i');
            const htmlTag = document.documentElement;

            // Load theme dari localStorage
            const savedTheme = localStorage.getItem('theme') || 'light';
            htmlTag.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            themeToggle.addEventListener('click', function() {
                const currentTheme = htmlTag.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                htmlTag.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                }
            }

            // --- Fitur Sidebar (Kode Asli Anda) ---
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');

            if (sidebarToggle && sidebar) {
                function toggleSidebar() {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.toggle('active');
                    } else {
                        body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebarState', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
                    }
                }
                sidebarToggle.addEventListener('click', toggleSidebar);

                const savedState = localStorage.getItem('sidebarState');
                if (window.innerWidth > 992) {
                    if (savedState === 'collapsed') body.classList.add('sidebar-collapsed');
                } else {
                    body.classList.remove('sidebar-collapsed');
                    sidebar.classList.remove('active');
                }

                document.querySelectorAll('.sidebar-nav a').forEach(link => {
                    const spanText = link.querySelector('span');
                    if (spanText) link.setAttribute('data-tooltip', spanText.textContent.trim());
                });

                document.addEventListener('click', function (e) {
                    if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                        if (!sidebar.contains(e.target) && e.target !== sidebarToggle && !sidebarToggle.contains(e.target)) {
                            sidebar.classList.remove('active');
                        }
                    }
                });

                window.addEventListener('resize', function () {
                    if (window.innerWidth > 992) {
                        sidebar.classList.remove('active');
                        if (localStorage.getItem('sidebarState') === 'collapsed') {
                            body.classList.add('sidebar-collapsed');
                        } else {
                            body.classList.remove('sidebar-collapsed');
                        }
                    } else {
                        body.classList.remove('sidebar-collapsed');
                        sidebar.classList.remove('active');
                    }
                });
            }

            // Ajax Error Handling
            $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
                let message = 'Terjadi kesalahan di server. Silakan coba lagi.';
                if (jqxhr.status === 419) message = 'Sesi Anda telah berakhir. Halaman akan dimuat ulang.';
                else if (jqxhr.responseJSON && jqxhr.responseJSON.message) message = jqxhr.responseJSON.message;

                Swal.fire({
                    title: `Error ${jqxhr.status}`,
                    text: message,
                    icon: 'error'
                }).then(() => {
                    if (jqxhr.status === 419) window.location.reload();
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>