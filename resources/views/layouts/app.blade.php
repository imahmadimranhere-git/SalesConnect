<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::current()->app_name }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1e293b;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 1rem;
            transition: transform 0.3s ease;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar .brand {
            color: #fff;
            font-weight: 600;
            padding: 0 1.25rem 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar .nav {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
            flex-wrap: nowrap !important;

            /* Hide scrollbar visually, but keep scrolling functional */
            scrollbar-width: none;      /* Firefox */
            -ms-overflow-style: none;   /* IE/Edge */
        }

        .sidebar .nav::-webkit-scrollbar {
            display: none;              /* Chrome, Safari, Edge (Chromium) */
        }

.sidebar .nav-link {
    color: rgba(255,255,255,0.75);
    padding: 0.45rem 1.25rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

        .sidebar .nav-link i {
    font-size: 0.95rem;
    flex-shrink: 0;
}

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 1.25rem;
        }

        .sidebar-footer .user-name {
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-footer .user-role {
            display: inline-block;
            background-color: rgba(13, 110, 253, 0.25);
            color: #8ab4ff;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 4px;
            margin-top: 2px;
        }

        /* Main content area shifts right of sidebar on large screens */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        /* Floating hamburger toggle (mobile only) */
        #sidebarToggle {
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 1030;
            width: 38px;
            height: 38px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        #sidebarToggle i {
            font-size: 1.1rem;
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
            .content-wrapper {
                padding-top: 4.5rem !important;
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
            @if (auth()->check() && auth()->user()->company && auth()->user()->company->logo)
                <img src="{{ asset('storage/' . auth()->user()->company->logo) }}" alt="Logo" style="height: 28px; width: 28px; object-fit: cover; border-radius: 4px; flex-shrink: 0;">
            @endif
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ auth()->check() && auth()->user()->company ? auth()->user()->company->name : \App\Models\Setting::current()->app_name }}
            </span>
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
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.visits.index') }}" class="nav-link {{ request()->routeIs('admin.visits.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i> Visits
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.route-history.index') }}" class="nav-link {{ request()->routeIs('admin.route-history.*') ? 'active' : '' }}">
                        <i class="bi bi-map"></i> Route History
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports.sales') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.company-profile.edit') }}" class="nav-link {{ request()->routeIs('admin.company-profile.*') ? 'active' : '' }}">
                        <i class="bi bi-building-gear"></i> Company Profile
                    </a>
                </li>
            @endif

            @if (auth()->user()->isDistributor())
                <li class="nav-item">
                    <a href="{{ route('distributor.dashboard') }}" class="nav-link {{ request()->routeIs('distributor.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributor.route-plan') }}" class="nav-link {{ request()->routeIs('distributor.route-plan') ? 'active' : '' }}">
                        <i class="bi bi-signpost-split"></i> Route Plan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributor.my-shops.index') }}" class="nav-link {{ request()->routeIs('distributor.my-shops.*') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i> My Shops
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributor.orders.index') }}" class="nav-link {{ request()->routeIs('distributor.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributor.products.index') }}" class="nav-link {{ request()->routeIs('distributor.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributor.reports.sales') }}" class="nav-link {{ request()->routeIs('distributor.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributor.profile.show') }}" class="nav-link {{ request()->routeIs('distributor.profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>
            @endif

            @if (auth()->user()->isShopkeeper())
                <li class="nav-item">
                    <a href="{{ route('shopkeeper.dashboard') }}" class="nav-link {{ request()->routeIs('shopkeeper.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shopkeeper.products.index') }}" class="nav-link {{ request()->routeIs('shopkeeper.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shopkeeper.orders.index') }}" class="nav-link {{ request()->routeIs('shopkeeper.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shopkeeper.visits.index') }}" class="nav-link {{ request()->routeIs('shopkeeper.visits.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i> Visits
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shopkeeper.profile.show') }}" class="nav-link {{ request()->routeIs('shopkeeper.profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>
            @endif

        </ul>

        {{-- ============ SIDEBAR FOOTER — LOGOUT (Fixed) ============ --}}
        @auth
            <div class="sidebar-footer">
                <div class="user-name">
                    {{ auth()->user()->name }}
                    <div>
                        <span class="user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="main-content" id="mainContent">

        <button class="btn btn-light d-lg-none" id="sidebarToggle" type="button">
            <i class="bi bi-list"></i>
        </button>

        <div class="container-fluid py-4 content-wrapper">

            {{-- All flash messages are handled centrally here, on every page --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                </div>
            @endif

         @if (session('generated_password'))
    <div class="alert alert-warning position-relative pe-5" id="passwordAlert">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="document.getElementById('passwordAlert').remove()"></button>

        <strong>Save this password now — it will not be shown again:</strong>

        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span>Email:</span>
            <code id="genEmail">{{ session('generated_email') }}</code>
            <button type="button" class="btn btn-sm btn-outline-dark copy-btn" data-target="genEmail">
                <i class="bi bi-clipboard"></i> Copy
            </button>
        </div>

        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span>Password:</span>
            <code id="genPassword">{{ session('generated_password') }}</code>
            <button type="button" class="btn btn-sm btn-outline-dark copy-btn" data-target="genPassword">
                <i class="bi bi-clipboard"></i> Copy
            </button>
        </div>
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

        document.addEventListener('DOMContentLoaded', function () {

        // Copy-to-clipboard buttons for generated credentials
document.querySelectorAll('.copy-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const targetId = button.getAttribute('data-target');
        const text = document.getElementById(targetId).innerText;

        navigator.clipboard.writeText(text).then(function () {
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';

            setTimeout(function () {
                button.innerHTML = originalHtml;
            }, 1500);
        });
    });
});

            // Auto-dismiss every alert after 3 seconds
            const alerts = document.querySelectorAll('.alert-dismissible');

            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.classList.remove('show');
                    setTimeout(function () {
                        alert.remove();
                    }, 300);
                }, 3000);
            });

            // Fix dropdown menus getting clipped inside scrollable tables
            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (el) {
                new bootstrap.Dropdown(el, {
                    popperConfig: {
                        strategy: 'fixed'
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>