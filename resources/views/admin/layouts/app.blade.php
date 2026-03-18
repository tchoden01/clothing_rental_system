<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Rentique')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --paper: #F5F5F5;
            --ink: #333333;
            --muted: #666666;
            --sidebar-top: #800020;
            --sidebar-bottom: #660018;
            --accent: #A0003A;
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
                radial-gradient(circle at 18% 10%, rgba(160, 0, 58, 0.08), transparent 38%),
                radial-gradient(circle at 85% 12%, rgba(128, 0, 32, 0.08), transparent 34%),
                linear-gradient(180deg, #FFFFFF, #F5F5F5);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-header {
            background: #fff;
            border-bottom: 1px solid #ded8cc;
            position: sticky;
            top: 0;
            z-index: 1100;
            padding: 0;
        }

        .admin-header-inner {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 0.8rem;
            min-height: 68px;
            padding: 0.65rem 1.1rem;
        }

        .admin-brand {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            min-width: 220px;
            gap: 0;
        }

        .admin-logo-text {
            font-size: 2rem;
            letter-spacing: 0.2em;
            font-weight: 800;
            color: #262626;
            text-transform: uppercase;
        }

        .admin-logo-text .logo-q {
            color: #800020;
        }

        .admin-brand:hover {
            opacity: 0.96;
        }

        .admin-menu-row {
            border-top: 1px solid #ece6d9;
            border-bottom: 1px solid #ece6d9;
            background: #fff;
        }

        .admin-menu-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2.3rem;
            min-height: 52px;
            overflow: visible;
            padding: 0 1rem;
        }

        .admin-menu-links::-webkit-scrollbar {
            height: 0;
        }

        .admin-menu-link {
            color: #231f1b;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.98rem;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            padding: 0.35rem 0;
            display: inline-flex;
            align-items: center;
        }

        .admin-menu-link.is-accent {
            color: #800020;
        }

        .admin-menu-links .dropdown {
            position: relative;
        }

        .admin-menu-links .dropdown-toggle::after {
            margin-left: 0.35rem;
            vertical-align: 0.1em;
            border-top-width: 0.35em;
            border-right-width: 0.3em;
            border-left-width: 0.3em;
        }

        .admin-menu-dropdown {
            border: 1px solid #e1d9ca;
            border-radius: 10px;
            padding: 0.45rem;
            min-width: 230px;
            max-height: 320px;
            overflow-y: auto;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
            margin-top: 0.4rem;
        }

        .admin-menu-dropdown .dropdown-item {
            font-size: 0.92rem;
            font-weight: 600;
            color: #2a2621;
            border-radius: 7px;
            padding: 0.5rem 0.7rem;
        }

        .admin-menu-dropdown .dropdown-item:hover,
        .admin-menu-dropdown .dropdown-item:focus {
            background-color: #F5F5F5;
            color: #800020;
        }

        .admin-menu-link:hover {
            color: #800020;
            border-bottom-color: #A0003A;
        }

        .admin-actions {
            justify-self: end;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .admin-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 999px;
            padding: 0.45rem 0.9rem;
            border: 1px solid #d6ccba;
            color: #3f362d;
            background: #fff;
        }

        .admin-chip:hover {
            color: #fff;
            border-color: #800020;
            background: #800020;
        }

        .btn:hover,
        button.btn:hover,
        a.btn:hover {
            background-color: #660018 !important;
            border-color: #660018 !important;
            color: #fff !important;
        }

        .admin-user {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border: 1px solid #d6ccba;
            background: #fff;
            border-radius: 999px;
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
            min-height: calc(100vh - 72px);
            flex: 1;
            margin-bottom: 1rem;
        }

        .admin-sidebar {
            background:
                linear-gradient(180deg, rgba(128, 0, 32, 0.97), rgba(102, 0, 24, 0.98)),
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
            top: 72px;
            height: calc(100vh - 72px);
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
            background: rgba(160, 0, 58, 0.2);
            border-left-color: #A0003A;
            color: #fff;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1.03rem;
        }

        .notif-badge {
            margin-left: auto;
            min-width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #A0003A;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 20px;
            text-align: center;
            padding: 0 6px;
            flex-shrink: 0;
        }

        .sidebar-badge {
            margin-left: auto;
            background: #A0003A;
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
            padding-bottom: 2rem;
        }

        .back-arrow-btn {
            position: fixed;
            left: 0.9rem;
            top: 6.5rem;
            width: 40px;
            height: 40px;
            border: 1px solid rgba(0, 0, 0, 0.14);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.95);
            color: #800020;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            z-index: 1200;
        }

        .back-arrow-btn:hover {
            color: #660018;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.16);
        }

        .admin-footer {
            background: #800020;
            color: rgba(255, 255, 255, 0.92);
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            padding: 0.8rem 1.25rem;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: auto;
            width: 100%;
        }

        @media (max-width: 991px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                position: static;
                height: auto;
            }

            .admin-header-inner {
                grid-template-columns: auto 1fr auto;
            }

            .admin-menu-links {
                justify-content: flex-start;
                gap: 1.25rem;
                padding: 0 0.75rem;
                overflow-x: auto;
                overflow-y: visible;
            }

            .admin-brand {
                justify-self: center;
                min-width: 170px;
            }

            .admin-logo-text {
                font-size: 1.35rem;
                letter-spacing: 0.14em;
            }

            .admin-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.2rem;
            }

            .back-arrow-btn {
                top: 5.8rem;
                left: 0.7rem;
                width: 36px;
                height: 36px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @unless(request()->routeIs('admin.dashboard'))
        <button type="button" class="back-arrow-btn" aria-label="Go back" title="Go back" onclick="goBackOrAdminHome()">
            <i class="bi bi-arrow-left"></i>
        </button>
    @endunless

    <header class="admin-header">
        <div class="admin-header-inner">
            <div></div>
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <span class="admin-logo-text">RENTI<span class="logo-q">Q</span>UE</span>
            </a>

            <div class="admin-actions">
                <a href="{{ route('admin.dashboard') }}" class="admin-chip d-none d-lg-inline-flex">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="admin-chip d-none d-lg-inline-flex" aria-label="Browse items">
                    <i class="bi bi-search"></i>
                </a>
                <div class="dropdown">
                    <div class="admin-user" data-bs-toggle="dropdown" aria-expanded="false">
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
            </div>
        </div>

        <div class="admin-menu-row">
            <nav class="admin-menu-links" aria-label="Admin primary navigation">
                <a href="{{ route('products.index') }}" class="admin-menu-link">Browse All</a>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="admin-menu-link">New Arrivals</a>

                <div class="dropdown">
                    <a href="#" class="admin-menu-link dropdown-toggle" id="adminCategoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                    <ul class="dropdown-menu admin-menu-dropdown" aria-labelledby="adminCategoriesDropdown">
                        @php
                            $adminNavCategories = \App\Models\Category::where('is_approved', true)->orderBy('name')->get();
                            if ($adminNavCategories->isEmpty()) {
                                $adminNavCategories = \App\Models\Category::orderBy('name')->get();
                            }
                        @endphp
                        <li><a class="dropdown-item" href="{{ route('products.index') }}">All Categories</a></li>
                        @if($adminNavCategories->isNotEmpty())
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        @forelse($adminNavCategories as $adminNavCategory)
                            <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $adminNavCategory->name]) }}">{{ $adminNavCategory->name }}</a></li>
                        @empty
                            <li><span class="dropdown-item-text">No categories available</span></li>
                        @endforelse
                    </ul>
                </div>

                <a href="{{ route('products.index', ['search' => 'wedding']) }}" class="admin-menu-link">Weddings</a>
                <a href="{{ route('products.index', ['sort' => 'price_asc']) }}" class="admin-menu-link is-accent">Clearance</a>
            </nav>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar d-flex flex-column">
            @php
                $pendingSellersBadgeCount = 0;
                if (isset($pendingSellers)) {
                    $pendingSellersBadgeCount = is_countable($pendingSellers) ? count($pendingSellers) : (int) $pendingSellers;
                }
            @endphp
            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i><span>Dashboard</span></a></li>
                <li><a href="{{ route('admin.customers') }}" class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}"><i class="bi bi-people"></i><span>Users</span></a></li>
                <li><a href="{{ route('admin.sellers') }}" class="sidebar-link {{ request()->routeIs('admin.sellers*') ? 'active' : '' }}"><i class="bi bi-shop"></i><span>Sellers</span>@if($pendingSellersBadgeCount > 0)<span class="sidebar-badge">{{ $pendingSellersBadgeCount }}</span>@endif</a></li>
                <li><a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}"><i class="bi bi-ui-checks-grid"></i><span>Item Approvals</span></a></li>
                <li><a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"><i class="bi bi-tags"></i><span>Categories</span></a></li>
                <li><a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"><i class="bi bi-bag-check"></i><span>Orders</span></a></li>
                <li><a href="{{ route('admin.orders') }}" class="sidebar-link"><i class="bi bi-arrow-repeat"></i><span>Returns</span></a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="bi bi-credit-card"></i><span>Payments</span></a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="bi bi-graph-up-arrow"></i><span>Commission Reports</span></a></li>
                <li><a href="{{ route('admin.pickups') }}" class="sidebar-link {{ request()->routeIs('admin.pickups*') ? 'active' : '' }}"><i class="bi bi-truck"></i><span>Pickup Management</span></a></li>
                <li><a href="{{ route('admin.notifications') }}" class="sidebar-link {{ request()->routeIs('admin.notifications') ? 'active' : '' }}"><i class="bi bi-bell"></i><span>Notifications</span>@if(($adminPendingNotificationsCount ?? 0) > 0)<span class="notif-badge" title="{{ $adminPendingNotificationsCount }} pending admin item(s)">{{ $adminPendingNotificationsCount }}</span>@endif</a></li>
                <li><a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="bi bi-gear"></i><span>Settings</span></a></li>
            </ul>

            <div class="sidebar-footer">
                <div style="font-weight: 700; color: #FFFFFF; margin-bottom: 0.4rem;">Rentique</div>
                <div>&copy; {{ date('Y') }} Rentique. All rights reserved.</div>
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

    <footer class="admin-footer">
        <div><strong>Rentique Admin Portal</strong></div>
        <div>&copy; {{ date('Y') }} Rentique. All rights reserved.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBackOrAdminHome() {
            if (window.history.length > 1) {
                window.history.back();
                return;
            }
            window.location.href = "{{ route('admin.dashboard') }}";
        }
    </script>
    @stack('scripts')
</body>
</html>
