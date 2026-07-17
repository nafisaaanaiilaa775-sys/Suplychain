<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - RiskIntel Supply Chain</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom-theme.css') }}">
    
    @yield('styles')
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-none d-md-flex flex-column justify-content-between py-4 position-fixed h-100">
                <div>
                    <!-- Logo / Brand -->
                    <div class="px-4 mb-4 d-flex align-items-center gap-3">
                        <div class="bg-indigo p-2 rounded-3 text-white d-flex align-items-center justify-content-center" style="background-color: var(--accent-color); width: 40px; height: 40px;">
                            <i class="fa-solid fa-earth-asia fa-spin" style="--fa-animation-duration: 20s;"></i>
                        </div>
                        <span class="fs-4 fw-bold tracking-wide" style="font-family: 'Outfit';">RiskIntel</span>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('map') }}" class="nav-link {{ Route::is('map') ? 'active' : '' }}">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>Weather & Ports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('currency') }}" class="nav-link {{ Route::is('currency') ? 'active' : '' }}">
                                <i class="fa-solid fa-coins"></i>
                                <span>Currency & News</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('compare') }}" class="nav-link {{ Route::is('compare') ? 'active' : '' }}">
                                <i class="fa-solid fa-right-left"></i>
                                <span>Compare</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                                <i class="fa-solid fa-user-shield"></i>
                                <span>Admin Panel</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Bottom Profile / Logout -->
                <div class="px-4">
                    <hr style="border-top: 1px solid var(--border-color)">
                    <div class="d-flex align-items-center gap-3 py-2">
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed=Guest+User&backgroundColor=2563eb" alt="avatar" class="rounded-circle" style="width: 36px; height: 36px; border: 1.5px solid var(--accent-color);">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 text-truncate text-white" style="font-size: 0.95rem;">Guest User</h6>
                            <small class="text-secondary text-truncate d-block" style="font-size: 0.75rem;">guest@riskintel.local</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 min-vh-100 d-flex flex-column">
                <!-- Top Header -->
                <nav class="navbar top-navbar d-flex align-items-center justify-content-between sticky-top">
                    <!-- Mobile toggle -->
                    <button class="btn btn-outline-secondary d-md-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="fa-solid fa-bars text-secondary"></i>
                    </button>
                    
                    <div class="d-none d-md-flex align-items-center position-relative w-25">
                        <i class="fa-solid fa-magnifying-glass text-secondary position-absolute ms-3"></i>
                        <input type="text" class="form-control custom-input ps-5 w-100" placeholder="Search country, port, or news...">
                    </div>
                    
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-secondary d-none d-sm-inline" style="font-size: 0.85rem;">
                            <i class="fa-regular fa-clock me-1"></i> <span id="current-time">09:00:00 WIB</span>
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-light bg-transparent border-0 d-flex align-items-center gap-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-dark d-none d-sm-inline">Guest</span>
                                <i class="fa-solid fa-chevron-down text-secondary" style="font-size: 0.8rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end glass-card border-secondary mt-2 shadow" aria-labelledby="userMenu">
                                <li><a class="dropdown-item py-2" href="#"><i class="fa-regular fa-user me-2 text-secondary"></i> Profil</a></li>
                                <li><a class="dropdown-item py-2" href="#"><i class="fa-regular fa-bookmark me-2 text-secondary"></i> Watchlist</a></li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="#"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
                
                <!-- Main Dashboard Viewport -->
                <main class="flex-grow-1 p-4">
                    @yield('content')
                </main>
                
                <!-- Footer -->
                <footer class="text-center py-3 border-top border-secondary mt-auto text-secondary" style="font-size: 0.85rem; background-color: var(--bg-secondary);">
                    <span>&copy; 2026 RiskIntel Supply Chain Intelligence. All rights reserved.</span>
                </footer>
            </div>
        </div>
    </div>
    
    <!-- Mobile Offcanvas Sidebar HTML -->
    <div class="offcanvas offcanvas-start bg-dark text-white border-end border-secondary" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="background-color: var(--bg-secondary) !important;">
        <div class="offcanvas-header border-b border-secondary">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-indigo p-2 rounded-3 text-white d-flex align-items-center justify-content-center" style="background-color: var(--accent-color); width: 35px; height: 35px;">
                    <i class="fa-solid fa-earth-asia"></i>
                </div>
                <span class="fs-5 fw-bold" style="font-family: 'Outfit';">RiskIntel</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between p-0 py-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }} py-3">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('map') }}" class="nav-link {{ Route::is('map') ? 'active' : '' }} py-3">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>Weather & Ports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('currency') }}" class="nav-link {{ Route::is('currency') ? 'active' : '' }} py-3">
                        <i class="fa-solid fa-coins"></i>
                        <span>Currency & News</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('compare') }}" class="nav-link {{ Route::is('compare') ? 'active' : '' }} py-3">
                        <i class="fa-solid fa-right-left"></i>
                        <span>Compare</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }} py-3">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Admin Panel</span>
                    </a>
                </li>
            </ul>
            <div class="px-4">
                <hr class="border-secondary">
                <div class="d-flex align-items-center gap-3 py-2">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed=Guest+User&backgroundColor=2563eb" alt="avatar" class="rounded-circle" style="width: 36px; height: 36px; border: 1.5px solid var(--accent-color);">
                    <div>
                        <h6 class="mb-0 text-white" style="font-size: 0.95rem;">Guest User</h6>
                        <small class="text-secondary" style="font-size: 0.75rem;">guest@riskintel.local</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Clock and General Utilities Script -->
    <script>
        // Digital Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            const element = document.getElementById('current-time');
            if (element) element.textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    
    @yield('scripts')
</body>
</html>
