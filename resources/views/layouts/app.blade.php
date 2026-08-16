<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SalesConnect') }}</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #1e293b;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 1rem;
            transition: transform 0.3s ease;
            z-index: 1040;
        }

        .sidebar .brand {
            color: #fff;
            font-weight: 600;
            padding: 0 1.25rem 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 0.5rem;
            display: block;
            text-decoration: none;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 0;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }

        /* Main content area shifts right of sidebar on large screens */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Mobile: sidebar hidden by default, slides in */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1035;
        }
        .sidebar-backdrop.show {
            display: block;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 576px) {
            .container, .container-fluid {
                padding-left: 12px;
                padding-right: 12px;
            }
        }
    </style>
</head>
<body>

    {{-- ============ SIDEBAR ============ --}}
    <aside class="sidebar" id="sidebar">
       <a href="#" class="brand">
    {{ auth()->check() && auth()->user()->company ? auth()->user()->company->name : \App\Models\Setting::current()->app_name }}
</a>

        <ul class="nav flex-column">

            @if (auth()->user()->isSuperAdmin())
                <li class="nav-item">
                    <a href="{{ route('super-admin.dashboard') }}" class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.admins.index') }}" class="nav-link {{ request()->routeIs('super-admin.admins.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Manage Admins
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.companies.index') }}" class="nav-link {{ request()->routeIs('super-admin.companies.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Companies
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.activity-logs.index') }}" class="nav-link {{ request()->routeIs('super-admin.activity-logs.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Activity Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.settings.edit') }}" class="nav-link {{ request()->routeIs('super-admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            @endif

           @if (auth()->user()->isAdmin())
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.distributors.index') }}" class="nav-link {{ request()->routeIs('admin.distributors.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Distributors
        </a>
    </li>
    

<li class="nav-item">
    <a href="{{ route('admin.shops.index') }}" class="nav-link {{ request()->routeIs('admin.shops.*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt"></i> Shops
    </a>
</li>
@endif

            @if (auth()->user()->isDistributor())
                <li class="nav-item">
                    <a href="{{ route('distributor.dashboard') }}" class="nav-link {{ request()->routeIs('distributor.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
            @endif

            @if (auth()->user()->isShopkeeper())
                <li class="nav-item">
                    <a href="{{ route('shopkeeper.dashboard') }}" class="nav-link {{ request()->routeIs('shopkeeper.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
            @endif

        </ul>
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="main-content" id="mainContent">

        <nav class="navbar topbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggle" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="ms-auto d-flex align-items-center gap-3">
                    @auth
                        <span class="text-secondary small">
                            {{ auth()->user()->name }} ({{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }})
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">

            {{-- All flash messages are handled centrally here, on every page --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('generated_password'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <strong>Save this password now — it will not be shown again:</strong><br>
                    Email: <code>{{ session('generated_email') }}</code><br>
                    Password: <code>{{ session('generated_password') }}</code>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        }

        toggleBtn.addEventListener('click', openSidebar);
        backdrop.addEventListener('click', closeSidebar);

        // Auto-dismiss every alert after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert-dismissible');

            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.classList.remove('show');
                    setTimeout(function () {
                        alert.remove();
                    }, 300);
                }, 3000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>