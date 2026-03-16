<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard - DrukWear')</title>
    
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
            background-color: #f5f5f5;
            color: #333;
        }
        
        /* Header */
        .seller-header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .seller-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .seller-brand img {
            width: 40px;
            height: 40px;
        }
        
        .seller-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .seller-brand-text .druk {
            color: #d97d3f;
        }
        
        .seller-brand-text .wear {
            color: #333;
        }
        
        .seller-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .seller-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .seller-nav a:hover {
            color: #d97d3f;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 8px;
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
            min-height: calc(100vh - 80px);
        }
        
        .seller-sidebar {
            width: 220px;
            background-color: #2c5f5f;
            color: white;
            padding: 1.5rem 0;
            position: sticky;
            top: 80px;
            height: calc(100vh - 80px);
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-brand-text {
            font-size: 1.3rem;
            font-weight: 700;
        }
        
        .sidebar-brand .druk {
            color: #d97d3f;
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
            background-color: rgba(215, 125, 63, 0.2);
            color: white;
            border-left: 4px solid #d97d3f;
        }
        
        .sidebar-nav-link i {
            font-size: 1.1rem;
            width: 20px;
        }
        
        .sidebar-nav-badge {
            margin-left: auto;
            background-color: #d97d3f;
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
            overflow-x: hidden;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
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
            color: #333;
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
            background-color: #2c5f5f;
            border-color: #2c5f5f;
        }
        
        .btn-primary:hover {
            background-color: #234848;
            border-color: #234848;
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
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="seller-header">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('seller.dashboard') }}" class="seller-brand">
                <i class="bi bi-gem" style="font-size: 1.8rem; color: #d97d3f;"></i>
                <span class="seller-brand-text">
                    <span class="druk">Druk</span><span class="wear">Wear</span>
                </span>
            </a>
            
            <div class="seller-nav d-none d-md-flex">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('products.index') }}">Browse</a>
                <a href="{{ route('home') }}#how-it-works">How it Works</a>
                <a href="{{ route('home') }}#about">About Us</a>
            </div>
            
            <div class="dropdown">
                <div class="user-profile" data-bs-toggle="dropdown">
                    <i class="bi bi-search" style="font-size: 1.2rem; color: #666;"></i>
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
    </header>
    
    <!-- Layout -->
    <div class="seller-layout">
        <!-- Sidebar -->
        <aside class="seller-sidebar">
            <div class="sidebar-brand">
                <div class="seller-brand">
                    <i class="bi bi-gem" style="font-size: 1.5rem; color: #d97d3f;"></i>
                    <span class="sidebar-brand-text">
                        <span class="druk">Druk</span><span style="color: white;">Wear</span>
                    </span>
                </div>
            </div>
            
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
                    <a href="{{ route('seller.orders') }}" class="sidebar-nav-link {{ request()->routeIs('seller.orders*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i>
                        <span>Orders</span>
                        @if(isset($pendingOrders) && $pendingOrders > 0)
                            <span class="sidebar-nav-badge">{{ $pendingOrders }}</span>
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
