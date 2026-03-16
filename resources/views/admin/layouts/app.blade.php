<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - DrukWear')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --paper: #f4ede3;
            --ink: #2f2a23;
            --muted: #695d4d;
            --sidebar-top: #2f5a55;
            --sidebar-bottom: #254944;
            --accent: #d79f45;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 18% 10%, rgba(113, 86, 49, 0.08), transparent 38%),
                radial-gradient(circle at 85% 12%, rgba(66, 111, 95, 0.08), transparent 34%),
                linear-gradient(180deg, #f7efe4, #efe3d3);
            min-height: 100vh;
        }

        .admin-header {
            height: 76px;
            background: rgba(255, 255, 255, 0.74);
            border-bottom: 1px solid rgba(113, 93, 68, 0.22);
            backdrop-filter: blur(3px);
            position: sticky;
            top: 0;
            z-index: 1100;
            padding: 0 1.1rem;
        }

        .admin-brand {
            text-decoration: none;
            color: #f0c777;
            font-size: 2rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .admin-brand i {
            color: #d7a350;
        }

        .admin-top-nav {
            display: flex;
            align-items: center;
            gap: 1.6rem;
            margin-left: 1.2rem;
        }

        .admin-top-link {
            color: #3f362d;
            text-decoration: none;
            font-weight: 500;
        }

        .admin-top-link:hover {
            color: #9b6b1f;
        }

        .admin-user {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border: 1px solid rgba(112, 91, 65, 0.24);
            background: rgba(255, 255, 255, 0.78);
            border-radius: 10px;
            padding: 0.4rem 0.7rem;
            cursor: pointer;
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(130deg, #f5d7b2, #d2a273);
            color: #3f2a1e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.86rem;
        }

        .admin-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: calc(100vh - 76px);
        }

        .admin-sidebar {
            background:
                linear-gradient(180deg, rgba(42, 82, 76, 0.97), rgba(34, 67, 62, 0.98)),
                repeating-linear-gradient(
                    -25deg,
                    rgba(255, 255, 255, 0.025),
                    rgba(255, 255, 255, 0.025) 9px,
                    rgba(0, 0, 0, 0.02) 9px,
                    rgba(0, 0, 0, 0.02) 18px
                );
            color: #fff;
            border-right: 1px solid rgba(255, 255, 255, 0.09);
            position: sticky;
            top: 76px;
            height: calc(100vh - 76px);
            overflow-y: auto;
        }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 1.15rem 0 1rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.72rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.09);
            color: #fff;
        }

        .sidebar-link.active {
            background: rgba(215, 159, 69, 0.18);
            border-left-color: #d6a655;
            color: #fff;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1.03rem;
        }

        .sidebar-badge {
            margin-left: auto;
            background: #d0823f;
            border-radius: 999px;
            padding: 0.12rem 0.42rem;
            font-size: 0.72rem;
            line-height: 1.2;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.82rem;
        }

        .admin-main {
            padding: 1.1rem 1.25rem 1.45rem;
        }

        @media (max-width: 991px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                position: static;
                height: auto;
            }

            .admin-top-nav {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header class="admin-header d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center min-w-0">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <i class="bi bi-gem"></i>
                <span style="font-size: 2rem; line-height: 1;">DrukWear</span>
            </a>

            <nav class="admin-top-nav d-none d-lg-flex">
                <a href="{{ route('home') }}" class="admin-top-link">Home</a>
                <a href="{{ route('products.index') }}" class="admin-top-link">Browse</a>
                <a href="{{ route('home') }}#how-it-works" class="admin-top-link">How it Works</a>
                <a href="{{ route('home') }}#about" class="admin-top-link">About Us</a>
            </nav>
        </div>

        <div class="dropdown">
            <div class="admin-user" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-search" style="font-size: 1rem; color: #7a6a56;"></i>
                <span class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <span class="d-none d-md-inline" style="font-size: 0.9rem; font-weight: 600; color: #433a30;">{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: #756654;"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar d-flex flex-column">
            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i><span>Dashboard</span></a></li>
                <li><a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"><i class="bi bi-people"></i><span>Users</span></a></li>
                <li><a href="{{ route('admin.sellers') }}" class="sidebar-link {{ request()->routeIs('admin.sellers*') ? 'active' : '' }}"><i class="bi bi-shop"></i><span>Sellers</span>@if(isset($pendingSellers) && $pendingSellers > 0)<span class="sidebar-badge">{{ $pendingSellers }}</span>@endif</a></li>
                <li><a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}"><i class="bi bi-ui-checks-grid"></i><span>Item Approvals</span></a></li>
                <li><a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"><i class="bi bi-bag-check"></i><span>Orders</span></a></li>
                <li><a href="{{ route('admin.orders') }}" class="sidebar-link"><i class="bi bi-arrow-repeat"></i><span>Returns</span></a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="bi bi-credit-card"></i><span>Payments</span></a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="bi bi-graph-up-arrow"></i><span>Commission Reports</span></a></li>
                <li><a href="{{ route('admin.orders') }}" class="sidebar-link"><i class="bi bi-truck"></i><span>Pickup Management</span></a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="bi bi-bell"></i><span>Notifications</span></a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="bi bi-gear"></i><span>Settings</span></a></li>
            </ul>

            <div class="sidebar-footer">
                <div style="font-weight: 700; color: #f0c777; margin-bottom: 0.4rem;">DrukWear</div>
                <div>&copy; {{ date('Y') }} DrukWear. All rights reserved.</div>
            </div>
        </aside>

        <main class="admin-main">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
