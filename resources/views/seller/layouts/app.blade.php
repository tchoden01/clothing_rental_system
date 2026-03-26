<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard - Rentique')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F5F5F5;
            color: #333333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-pagination {
            margin-top: 0.85rem;
        }

        .app-pagination nav[role="navigation"] > div:first-child {
            display: none;
        }

        .app-pagination nav[role="navigation"] > div:last-child {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .app-pagination nav[role="navigation"] span,
        .app-pagination nav[role="navigation"] a {
            font-size: 0.8rem;
            min-width: 1.9rem;
            min-height: 1.9rem;
            padding: 0.32rem 0.5rem;
            line-height: 1.1;
        }

        .app-pagination nav[role="navigation"] svg {
            width: 0.88rem;
            height: 0.88rem;
        }
        
        /* Header */
        .seller-header {
            background-color: #fff;
            border-bottom: 1px solid #ded8cc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .seller-header-inner {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 0.8rem;
            min-height: 68px;
            padding: 0.65rem 1.25rem;
        }
        
        .seller-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            justify-self: center;
            line-height: 1;
            gap: 0;
        }

        .seller-logo-text {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #262626;
        }

        .seller-logo-text .logo-q {
            color: #800020;
        }

        
        .seller-nav {
            border-top: 1px solid #ece6d9;
            border-bottom: 1px solid #ece6d9;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2.3rem;
            min-height: 52px;
            overflow: visible;
            padding: 0 1rem;
        }

        .seller-nav::-webkit-scrollbar {
            height: 0;
        }
        
        .seller-nav a {
            color: #231f1b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 0.98rem;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            padding: 0.35rem 0;
            display: inline-flex;
            align-items: center;
        }

        .seller-nav .is-accent {
            color: #800020;
        }

        .seller-nav .dropdown {
            position: relative;
        }

        .seller-nav .dropdown-toggle::after {
            margin-left: 0.35rem;
            vertical-align: 0.1em;
            border-top-width: 0.35em;
            border-right-width: 0.3em;
            border-left-width: 0.3em;
        }

        .seller-menu-dropdown {
            border: 1px solid #e1d9ca;
            border-radius: 10px;
            padding: 0.45rem;
            min-width: 230px;
            max-height: 320px;
            overflow-y: auto;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
            margin-top: 0.4rem;
        }

        .seller-menu-dropdown .dropdown-item {
            font-size: 0.92rem;
            font-weight: 600;
            color: #2a2621;
            border-radius: 7px;
            padding: 0.5rem 0.7rem;
        }

        .seller-menu-dropdown .dropdown-item:hover,
        .seller-menu-dropdown .dropdown-item:focus {
            background-color: #F5F5F5;
            color: #800020;
        }
        
        .seller-nav a:hover {
            color: #800020;
            border-bottom-color: #A0003A;
        }

        .seller-actions {
            justify-self: end;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .seller-chip {
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

        .seller-chip:hover {
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
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            border: 1px solid #d6ccba;
            transition: background-color 0.3s;
        }
        
        .user-profile:hover {
            background-color: #f8f8f8;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #555;
        }
        
        /* Sidebar */
        .seller-layout {
            display: flex;
            min-height: calc(100vh - 72px);
            flex: 1;
            margin-bottom: 1rem;
        }
        
        .seller-sidebar {
            width: 220px;
            background: linear-gradient(180deg, #800020 0%, #660018 100%);
            color: white;
            padding: 1.5rem 0;
            position: sticky;
            top: 72px;
            height: calc(100vh - 72px);
            overflow-y: auto;
        }
        
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-nav-item {
            margin-bottom: 0.3rem;
        }
        
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
        }
        
        .sidebar-nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-nav-link.active {
            background-color: rgba(160, 0, 58, 0.22);
            color: white;
            border-left: 4px solid #A0003A;
        }
        
        .sidebar-nav-link i {
            font-size: 1.1rem;
            width: 20px;
        }
        
        .sidebar-nav-badge {
            margin-left: auto;
            background-color: #A0003A;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Main Content */
        .seller-main {
            flex: 1;
            padding: 2rem;
            padding-bottom: 2.25rem;
            overflow-x: hidden;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 0;
        }
        
        /* Stat Cards */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--card-bg-1), var(--card-bg-2));
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 100px;
            height: 100px;
            opacity: 0.2;
        }
        
        .stat-card.blue {
            --card-bg-1: #4287f5;
            --card-bg-2: #2563eb;
        }
        
        .stat-card.orange {
            --card-bg-1: #f59e42;
            --card-bg-2: #f57c42;
        }
        
        .stat-card.amber {
            --card-bg-1: #f5b942;
            --card-bg-2: #f59e42;
        }
        
        .stat-card.green {
            --card-bg-1: #42a052;
            --card-bg-2: #2d7a3d;
        }
        
        .stat-card-title {
            font-size: 0.9rem;
            opacity: 0.95;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }
        
        .stat-card-subtitle {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .stat-card-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1.2rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Buttons */
        .btn {
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #800020;
            border-color: #800020;
        }
        
        .btn-primary:hover {
            background-color: #660018;
            border-color: #660018;
        }
        
        .btn-success {
            background-color: #42a052;
            border-color: #42a052;
        }
        
        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
        }
        
        /* Tables */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e0e0e0;
            padding: 0.75rem;
        }
        
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            border-radius: 6px;
        }

        .back-arrow-btn {
            position: fixed;
            left: 0.9rem;
            top: 6.5rem;
            width: 40px;
            height: 40px;
            border: 1px solid rgba(0, 0, 0, 0.12);
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

        .seller-footer {
            background: #800020;
            color: rgba(255, 255, 255, 0.92);
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            padding: 0.9rem 2rem;
            font-size: 0.9rem;
            margin-top: auto;
            width: 100%;
        }

        .seller-footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        
        @media (max-width: 992px) {
            .seller-sidebar {
                position: fixed;
                left: -220px;
                transition: left 0.3s;
                z-index: 1050;
            }
            
            .seller-sidebar.show {
                left: 0;
            }
            
            .seller-main {
                padding: 1.5rem;
            }

            .seller-header-inner {
                grid-template-columns: auto 1fr auto;
            }

            .seller-nav {
                justify-content: flex-start;
                gap: 1.25rem;
                padding: 0 0.75rem;
                overflow-x: auto;
                overflow-y: visible;
            }

            .seller-brand {
                justify-self: center;
            }

            .seller-logo-text {
                font-size: 1.28rem;
                letter-spacing: 0.11em;
            }

            .seller-footer {
                padding: 0.85rem 1rem;
            }

            .seller-footer-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
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
    @unless(request()->routeIs('seller.dashboard'))
        <button type="button" class="back-arrow-btn" aria-label="Go back" title="Go back" onclick="goBackOrSellerHome()">
            <i class="bi bi-arrow-left"></i>
        </button>
    @endunless

    <!-- Header -->
    <header class="seller-header">
        <div class="seller-header-inner">
            <div></div>

            <a href="{{ route('seller.dashboard') }}" class="seller-brand">
                <span class="seller-logo-text">RENTI<span class="logo-q">Q</span>UE</span>
            </a>
            
            <div class="seller-actions">
                <a href="{{ route('products.index') }}" class="seller-chip d-none d-lg-inline-flex" aria-label="Search items">
                    <i class="bi bi-search"></i>
                </a>
                <a href="{{ route('seller.dashboard') }}" class="seller-chip d-none d-lg-inline-flex">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <div class="dropdown">
                    <div class="user-profile" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                        </div>
                        <i class="bi bi-chevron-down" style="font-size: 0.8rem; color: #666;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}">Dashboard</a></li>
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

        <nav class="seller-nav" aria-label="Seller primary navigation">
            <a href="{{ route('products.index') }}">Browse All</a>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}">New Arrivals</a>

            <div class="dropdown">
                <a href="#" class="dropdown-toggle" id="sellerCategoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                <ul class="dropdown-menu seller-menu-dropdown" aria-labelledby="sellerCategoriesDropdown">
                    @php
                        $sellerNavCategories = \App\Models\Category::where('is_approved', true)->orderBy('name')->get();
                        if ($sellerNavCategories->isEmpty()) {
                            $sellerNavCategories = \App\Models\Category::orderBy('name')->get();
                        }
                    @endphp
                    <li><a class="dropdown-item" href="{{ route('products.index') }}">All Categories</a></li>
                    @if($sellerNavCategories->isNotEmpty())
                        <li><hr class="dropdown-divider"></li>
                    @endif
                    @forelse($sellerNavCategories as $sellerNavCategory)
                        <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $sellerNavCategory->name]) }}">{{ $sellerNavCategory->name }}</a></li>
                    @empty
                        <li><span class="dropdown-item-text">No categories available</span></li>
                    @endforelse
                </ul>
            </div>

            <a href="{{ route('products.index', ['search' => 'wedding']) }}">Weddings</a>
            <a href="{{ route('products.index', ['sort' => 'price_asc']) }}" class="is-accent">Clearance</a>
        </nav>
    </header>
    
    <!-- Layout -->
    <div class="seller-layout">
        <!-- Sidebar -->
        <aside class="seller-sidebar">
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.products') }}" class="sidebar-nav-link {{ request()->routeIs('seller.products*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Attire</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.categories.request') }}" class="sidebar-nav-link {{ request()->routeIs('seller.categories.request*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Category Requests</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.orders') }}" class="sidebar-nav-link {{ request()->routeIs('seller.orders*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i>
                        <span>Orders</span>
                        @if(isset($pendingOrders) && $pendingOrders > 0)
                            <span class="sidebar-nav-badge">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.notifications') }}" class="sidebar-nav-link {{ request()->routeIs('seller.notifications*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                        @if(($sellerUnreadNotificationsCount ?? 0) > 0)
                            <span class="sidebar-nav-badge">{{ $sellerUnreadNotificationsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.dashboard') }}#earnings-section" class="sidebar-nav-link">
                        <i class="bi bi-currency-exchange"></i>
                        <span>Earnings</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.dashboard', ['section' => 'customers']) }}" class="sidebar-nav-link">
                        <i class="bi bi-people"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('seller.dashboard', ['section' => 'insights']) }}" class="sidebar-nav-link">
                        <i class="bi bi-bar-chart"></i>
                        <span>Insights</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('profile') }}" class="sidebar-nav-link">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="seller-main">
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

    <footer class="seller-footer">
        <div class="seller-footer-inner">
            <div><strong>Rentique Seller Portal</strong></div>
            <div>&copy; {{ date('Y') }} Rentique. All rights reserved.</div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBackOrSellerHome() {
            if (window.history.length > 1) {
                window.history.back();
                return;
            }
            window.location.href = "{{ route('seller.dashboard') }}";
        }
    </script>
    
    @stack('scripts')
</body>
</html>
