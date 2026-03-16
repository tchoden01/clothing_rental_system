<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clothing Rental System')</title>
    
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
            color: #333;
            line-height: 1.6;
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: #fff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 1rem 0;
        }
        
        .navbar .container {
            display: flex;
            align-items: center;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #d97d3f !important;
            text-decoration: none;
            margin-right: 0;
        }
        
        .navbar-brand span:first-child {
            color: #d97d3f;
        }
        
        .navbar-brand span:last-child {
            color: #333;
        }
        
        .nav-center {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            gap: 2.5rem;
            margin: 0 2rem;
        }
        
        .nav-link-main {
            color: #333 !important;
            padding: 0.5rem 0;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
        }
        
        .nav-link-main:hover {
            color: #d97d3f !important;
            border-bottom: 2px solid #d97d3f;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .btn-login {
            color: #333;
            background-color: #fff;
            padding: 0.5rem 1.2rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .cart-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -7px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background-color: #d82323;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            line-height: 1;
        }
        
        .btn-login:hover {
            background-color: #f8f8f8;
            color: #333;
        }
        
        .btn-signup {
            background-color: #2c5f5f;
            color: white !important;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .btn-signup:hover {
            background-color: #234848;
        }
        
        /* Bootstrap Dropdown Overrides */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            border: none;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f8f8f8;
            color: #d97d3f;
        }
        
        .dropdown-item-text {
            padding: 0.6rem 1.5rem;
            color: #666;
            font-weight: 600;
        }
        
        .dropdown-divider {
            margin: 0.3rem 0;
        }
        
        .dropdown-toggle::after {
            margin-left: 0.4rem;
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 550px;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            color: white;
        }
        
        .hero-content {
            max-width: 600px;
        }
        
        .hero-logo {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .hero-logo span:first-child {
            color: #d97d3f;
        }
        
        .hero-logo span:last-child {
            color: white;
        }
        
        .hero-content h1 {
            font-size: 3rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        .hero-content h1 span {
            color: #f5c77e;
        }
        
        .hero-content p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .btn-browse {
            background-color: transparent;
            color: white;
            padding: 0.8rem 2rem;
            border: 2px solid white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-browse:hover {
            background-color: white;
            color: #333;
        }
        
        .btn-rent {
            background-color: #2c5f5f;
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-rent:hover {
            background-color: #234848;
            color: white;
        }
        
        /* How It Works Section */
        .how-it-works {
            padding: 4rem 0;
            background-color: #f9f9f9;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 3rem;
            color: #333;
        }
        
        .work-step {
            text-align: center;
        }
        
        .work-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background-color: #f0f0f0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .work-step h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #333;
        }
        
        .work-step p {
            color: #666;
            font-size: 0.95rem;
        }
        
        /* Featured Attire Section */
        .featured-attire {
            padding: 4rem 0;
        }
        
        .product-card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .product-card img {
            height: 300px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-card .card-body {
            padding: 1.5rem;
        }
        
        .product-card h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .product-card .price {
            color: #666;
            font-size: 0.95rem;
        }
        
        .btn-view-all {
            background-color: #2c5f5f;
            color: white;
            padding: 0.8rem 2.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-view-all:hover {
            background-color: #234848;
            color: white;
        }
        
        /* Why Choose Section */
        .why-choose {
            padding: 4rem 0;
            background: linear-gradient(135deg, #2c5f5f 0%, #4a8080 100%);
            color: white;
            position: relative;
        }
        
        .why-choose-content {
            max-width: 500px;
        }
        
        .why-choose h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }
        
        .why-choose h2 span {
            color: #f5c77e;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        
        .benefit-item i {
            color: #f5c77e;
        }
        
        .testimonial-box {
            background-color: white;
            color: #333;
            padding: 2rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e0e0e0;
        }
        
        .testimonial-name {
            font-weight: 600;
            color: #333;
        }
        
        .testimonial-location {
            font-size: 0.85rem;
            color: #666;
        }
        
        .testimonial-text {
            font-style: italic;
            color: #555;
            line-height: 1.6;
        }
        
        .testimonial-meta {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            font-size: 0.85rem;
            color: #999;
        }
        
        .btn-browse-attire {
            background-color: white;
            color: #2c5f5f;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-browse-attire:hover {
            background-color: #f8f8f8;
            color: #2c5f5f;
        }
        
        .why-choose-image {
            height: 100%;
            min-height: 400px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
        }
        
        /* Footer */
        footer {
            background-color: #2c5f5f;
            color: white;
            padding: 3rem 0 1.5rem;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .footer-logo {
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .footer-logo span:first-child {
            color: #d97d3f;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            color: white;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: #f5c77e;
        }
        
        .footer-contact {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 1.5rem;
            display: flex;
            gap: 2rem;
            font-size: 0.9rem;
        }
        
        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        /* Dropdown Menu Styles */
        .nav-dropdown {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            min-width: 260px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            border-radius: 8px;
            padding: 0.8rem 0;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            margin-top: 1rem;
        }
        
        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.75rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .nav-dropdown-item:hover {
            background-color: #f8f8f8;
            color: #d97d3f;
            padding-left: 1.8rem;
        }
        
        .nav-dropdown-item img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .nav-dropdown-item i {
            font-size: 1.2rem;
            color: #d97d3f;
            width: 24px;
            text-align: center;
        }
        
        .nav-dropdown-item .dropdown-text {
            flex: 1;
        }
        
        .nav-dropdown-item .dropdown-title {
            display: block;
            font-weight: 600;
            font-size: 0.95rem;
            color: #333;
        }
        
        .nav-dropdown-item .dropdown-subtitle {
            display: block;
            font-size: 0.8rem;
            color: #777;
            font-weight: 400;
        }
        
        .nav-dropdown-divider {
            height: 1px;
            background-color: #e5e5e5;
            margin: 0.5rem 1.2rem;
        }
        
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            .nav-center {
                flex-direction: column;
                gap: 0.5rem;
            }
            .hero-buttons {
                flex-direction: column;
            }
            .nav-dropdown-menu {
                position: static;
                transform: none;
                box-shadow: none;
                display: none;
                margin-top: 0.5rem;
                padding: 0.5rem 0;
            }
            .nav-dropdown:hover .nav-dropdown-menu {
                display: block;
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <span>Druk</span><span>Wear</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Center Navigation -->
                <div class="nav-center d-none d-lg-flex">
                    <a href="{{ route('home') }}" class="nav-link-main">Home</a>
                    
                    <!-- Browse Dropdown -->
                    <div class="nav-dropdown">
                        <a href="{{ route('products.index') }}" class="nav-link-main">Browse</a>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('products.index', ['category' => 'Gho']) }}" class="nav-dropdown-item">
                                <i class="bi bi-person"></i>
                                <span class="dropdown-title">Men's Gho</span>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'Kira']) }}" class="nav-dropdown-item">
                                <i class="bi bi-person-dress"></i>
                                <span class="dropdown-title">Women's Kira</span>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'Ceremonial']) }}" class="nav-dropdown-item">
                                <i class="bi bi-star"></i>
                                <span class="dropdown-title">Ceremonial Wear</span>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'Wedding']) }}" class="nav-dropdown-item">
                                <i class="bi bi-heart"></i>
                                <span class="dropdown-title">Wedding Attire</span>
                            </a>
                            <div class="nav-dropdown-divider"></div>
                            <a href="{{ route('products.index') }}" class="nav-dropdown-item">
                                <i class="bi bi-grid"></i>
                                <span class="dropdown-title">All Attire</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- How it Works Dropdown -->
                    <div class="nav-dropdown">
                        <a href="#how-it-works" class="nav-link-main">How it Works</a>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('products.index') }}" class="nav-dropdown-item">
                                <i class="bi bi-search"></i>
                                <div class="dropdown-text">
                                    <span class="dropdown-title">Browse Attire</span>
                                    <span class="dropdown-subtitle">Explore our collection</span>
                                </div>
                            </a>
                            <a href="{{ route('products.index') }}" class="nav-dropdown-item">
                                <i class="bi bi-calendar-check"></i>
                                <div class="dropdown-text">
                                    <span class="dropdown-title">Rent With Ease</span>
                                    <span class="dropdown-subtitle">Simple booking process</span>
                                </div>
                            </a>
                            <a href="{{ auth()->check() ? route('orders.index') : route('products.index') }}" class="nav-dropdown-item">
                                <i class="bi bi-arrow-clockwise"></i>
                                <div class="dropdown-text">
                                    <span class="dropdown-title">Return & Repeat</span>
                                    <span class="dropdown-subtitle">Easy returns</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- About Us Dropdown -->
                    <div class="nav-dropdown">
                        <a href="#about" class="nav-link-main">About Us</a>
                        <div class="nav-dropdown-menu">
                            <a href="#about" class="nav-dropdown-item">
                                <i class="bi bi-people"></i>
                                <div class="dropdown-text">
                                    <span class="dropdown-title">About DrukWear</span>
                                    <span class="dropdown-subtitle">Our Story and Mission</span>
                                </div>
                            </a>
                            <a href="#faqs" class="nav-dropdown-item">
                                <i class="bi bi-question-circle"></i>
                                <div class="dropdown-text">
                                    <span class="dropdown-title">FAQs</span>
                                    <span class="dropdown-subtitle">Common questions answered</span>
                                </div>
                            </a>
                            <a href="#contact" class="nav-dropdown-item">
                                <i class="bi bi-telephone"></i>
                                <div class="dropdown-text">
                                    <span class="dropdown-title">Contact Us</span>
                                    <span class="dropdown-subtitle">+975-443-7890</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="nav-right ms-auto">
                    @auth
                        <a href="{{ route('cart.index') }}" class="btn-login cart-link">
                            <i class="bi bi-cart"></i>
                            {{ Auth::user()->isCustomer() ? 'Cart' : 'Cart Monitor' }}
                            @if(($cartBadgeCount ?? 0) > 0)
                                <span class="cart-badge">{{ $cartBadgeCount > 99 ? '99+' : $cartBadgeCount }}</span>
                            @endif
                        </a>
                        
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-signup">Dashboard</a>
                        @elseif(Auth::user()->isSeller())
                            <a href="{{ route('seller.dashboard') }}" class="btn-signup">Dashboard</a>
                        @else
                            <a href="{{ route('orders.index') }}" class="btn-login">Orders</a>
                        @endif
                        
                        <div class="dropdown">
                            <a class="btn-login dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(!Auth::user()->isAdmin() && !Auth::user()->isSeller())
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                        <a href="{{ route('register') }}" class="btn-signup">Sign Up</a>
                        <a href="#" class="btn-login"><i class="bi bi-bell"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <span>Druk</span><span>Wear</span>
                </div>
                <div class="social-links">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="footer-contact">
                <div><i class="bi bi-telephone"></i> +975-421-7898</div>
                <div><i class="bi bi-envelope"></i> info@drukwear.bt</div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} DrukWear. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
