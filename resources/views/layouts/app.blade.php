<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rentique')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-shell {
            flex: 1 0 auto;
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .app-main {
            flex: 1 0 auto;
        }

        footer {
            margin-top: auto;
            flex-shrink: 0;
        }
        
        /* Header Styles */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1100;
            background: #fff;
            border-bottom: 1px solid #ded8cc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .site-utility {
            border-bottom: 1px solid #ece6d9;
            background: #fff;
        }

        .site-top-inner {
            min-height: 68px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 1rem;
            width: 100%;
            padding: 0 1rem;
        }

        .top-wordmark {
            text-decoration: none;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            color: #23201d;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
        }

        .top-wordmark .logo-q {
            color: #800020;
        }

        .utility-left {
            justify-self: start;
            display: flex;
            align-items: center;
            gap: 0.95rem;
            min-width: 0;
        }

        .utility-right {
            justify-self: end;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            white-space: nowrap;
        }

        .main-nav-links {
            display: flex;
            align-items: center;
            gap: 3.35rem;
            justify-content: center;
            min-width: 0;
            overflow: visible;
            min-height: 54px;
            padding: 0.2rem 0;
            width: 100%;
            max-width: 1220px;
        }

        .main-nav-links::-webkit-scrollbar {
            height: 0;
        }

        .main-nav-link {
            color: #211f1c;
            text-decoration: none;
            font-size: 1.04rem;
            font-weight: 500;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.28rem;
            padding: 0.42rem 0.02rem;
            border-bottom: 2px solid transparent;
        }

        .main-nav-links .dropdown {
            position: relative;
        }

        .main-nav-links .dropdown-toggle::after {
            margin-left: 0.35rem;
            vertical-align: 0.1em;
            border-top-width: 0.35em;
            border-right-width: 0.3em;
            border-left-width: 0.3em;
        }

        .main-nav-dropdown-menu {
            border: 1px solid #e1d9ca;
            border-radius: 10px;
            padding: 0.45rem;
            min-width: 230px;
            max-height: 320px;
            overflow-y: auto;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
            margin-top: 0.4rem;
        }

        .main-nav-dropdown-menu .dropdown-item {
            font-size: 0.92rem;
            font-weight: 600;
            color: #2a2621;
            border-radius: 7px;
            padding: 0.5rem 0.7rem;
        }

        .main-nav-dropdown-menu .dropdown-item:hover,
        .main-nav-dropdown-menu .dropdown-item:focus {
            background-color: #f4eee4;
            color: #800020;
        }

        .main-nav-link:hover {
            color: #800020;
            border-bottom-color: #A0003A;
        }

        .main-nav-link.is-accent {
            color: #800020;
        }

        .site-category-row {
            background: #fff;
            border-bottom: 1px solid #ece6d9;
        }

        .site-bottom-inner {
            width: 100%;
            padding: 0 1rem;
            display: flex;
            justify-content: center;
        }

        .icon-action {
            position: relative;
            color: #201d1a;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.2rem;
            min-width: 34px;
            font-size: 1rem;
            line-height: 1.1;
            padding: 0.2rem 0.35rem;
        }

        .icon-action i {
            font-size: 1.1rem;
        }

        .icon-action:hover {
            color: #A0003A;
        }

        .icon-badge {
            position: absolute;
            top: -3px;
            right: 3px;
            min-width: 17px;
            height: 17px;
            border-radius: 999px;
            background: #A0003A;
            color: #fff;
            border: 2px solid #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.66rem;
            font-weight: 700;
            line-height: 1;
            padding: 0 4px;
        }

        .utility-link {
            color: #1f1c18;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            letter-spacing: 0.01em;
        }

        .utility-link:hover {
            color: #800020;
        }

        .utility-divider {
            color: #b2a898;
            font-weight: 300;
        }

        .utility-search-form {
            display: inline-flex;
            align-items: center;
            border: 1px solid #d8d0c2;
            background: #efefef;
            border-radius: 12px;
            height: 40px;
            min-width: 290px;
            overflow: hidden;
        }

        .utility-search-icon {
            width: 42px;
            height: 42px;
            border: 0;
            border-right: 1px solid #d3cab9;
            background: transparent;
            color: #1d1a16;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            padding: 0;
        }

        .utility-search-input {
            border: 0;
            background: transparent;
            padding: 0 0.78rem;
            height: 100%;
            color: #222;
            font-size: 1rem;
            font-weight: 500;
            width: 310px;
            outline: none;
        }

        .utility-search-input::placeholder {
            color: #1f1c18;
            opacity: 0.9;
        }

        .utility-search-form:focus-within {
            border-color: #800020;
            box-shadow: 0 0 0 2px rgba(128, 0, 32, 0.12);
        }

        .rentique-brand {
            text-decoration: none;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            line-height: 1;
            min-width: 220px;
            gap: 0.04rem;
        }

        .rentique-logo-hanger {
            width: 78px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .rentique-logo-text {
            font-size: 2rem;
            letter-spacing: 0.2em;
            font-weight: 800;
            color: #262626;
            text-transform: uppercase;
        }

        .rentique-logo-text .logo-q {
            color: #800020;
        }

        .rentique-logo-swoosh {
            width: 112px;
            height: 12px;
            border-top: 2px solid #c58a3a;
            border-radius: 100%;
            margin-top: 0.08rem;
        }

        .rentique-brand:hover {
            opacity: 0.96;
        }

        .utility-pill {
            color: #fff;
            background: #800020;
            border-radius: 999px;
            padding: 0.45rem 1rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1;
            border: 1px solid #800020;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .utility-pill:hover {
            color: #fff;
            background: #660018;
            border-color: #660018;
        }

        .utility-outline {
            color: #3a342c;
            background: #fff;
            border-radius: 999px;
            padding: 0.45rem 1rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1;
            border: 1px solid #d6ccba;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .utility-outline:hover {
            color: #fff;
            border-color: #800020;
            background: #800020;
        }

        .site-category-row {
            background: #fff;
        }

        .category-links,
        .mobile-main-links {
            display: flex;
            align-items: center;
            gap: 1.9rem;
            min-height: 50px;
        }

        .category-link {
            color: #161310;
            text-decoration: none;
            font-size: 1.02rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .category-link:hover {
            color: #800020;
        }

        .category-link.is-accent {
            color: #800020;
        }

        .btn-login {
            color: #333333;
            background-color: #fff;
            padding: 0.5rem 1.2rem;
            border: 1px solid #ddd;
            border-radius: 999px;
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
            background-color: #A0003A;
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
            background-color: #800020;
            border-color: #800020;
            color: #fff;
        }
        
        .btn-signup {
            background-color: #800020;
            color: white !important;
            padding: 0.5rem 1.3rem;
            border-radius: 999px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-signup:hover {
            background-color: #660018;
        }

        .btn:hover,
        button.btn:hover,
        a.btn:hover {
            background-color: #660018 !important;
            border-color: #660018 !important;
            color: #fff !important;
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
            color: #333333;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: #F5F5F5;
            color: #800020;
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

        .back-arrow-btn {
            position: fixed;
            left: 1rem;
            top: 7.35rem;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.96);
            color: #800020;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            z-index: 1200;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .back-arrow-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.14);
            color: #660018;
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
            background-color: #660018;
            border-color: #660018;
            color: #fff;
        }
        
        .btn-rent {
            background-color: #800020;
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
            background-color: #660018;
            color: white;
        }
        
        /* How It Works Section */
        .how-it-works {
            padding: 4rem 0;
            background-color: #F5F5F5;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 3rem;
            color: #333333;
        }
        
        .work-step {
            text-align: center;
        }
        
        .work-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background-color: #F5F5F5;
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
            color: #333333;
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
            object-fit: contain;
            object-position: center;
            width: 100%;
            background: #f4f1eb;
        }
        
        .product-card .card-body {
            padding: 1.5rem;
        }
        
        .product-card h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333333;
        }
        
        .product-card .price {
            color: #666;
            font-size: 0.95rem;
        }
        
        .btn-view-all {
            background-color: #800020;
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
            background-color: #660018;
            color: white;
        }
        
        /* Why Choose Section */
        .why-choose {
            padding: 4rem 0;
            background: linear-gradient(135deg, #800020 0%, #660018 100%);
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
            color: #333333;
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
            color: #333333;
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
            color: #800020;
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
            background-color: #660018;
            color: #FFFFFF;
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
            background-color: #800020;
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
            color: #A0003A;
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
        
        @media (max-width: 991px) {
            .site-top-inner {
                grid-template-columns: 1fr;
                justify-items: stretch;
                gap: 0.55rem;
                padding: 0.65rem 1rem;
            }

            .utility-left,
            .utility-right {
                justify-content: center;
            }

            .top-wordmark {
                justify-content: center;
                text-align: center;
                font-size: 1.5rem;
                letter-spacing: 0.16em;
            }

            .site-bottom-inner {
                padding: 0 0.75rem;
            }

            .main-nav-links {
                justify-content: flex-start;
                gap: 1.3rem;
                overflow-x: auto;
                overflow-y: visible;
                padding-bottom: 0.2rem;
            }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            .hero-buttons {
                flex-direction: column;
            }

            .utility-pill,
            .utility-outline,
            .btn-login,
            .btn-signup {
                padding: 0.4rem 0.72rem;
                font-size: 0.82rem;
            }

            .utility-search-form {
                min-width: 0;
                width: 100%;
                max-width: 320px;
            }

            .utility-search-input {
                width: 100%;
            }

            .main-nav-link {
                font-size: 0.92rem;
            }

            .icon-action {
                min-width: 34px;
                font-size: 0.95rem;
            }

            .back-arrow-btn {
                top: 9.9rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-shell">
    @unless(request()->routeIs('home'))
        <button type="button" class="back-arrow-btn" aria-label="Go back" title="Go back" onclick="goBackOrHome()">
            <i class="bi bi-arrow-left"></i>
        </button>
    @endunless

    <header class="site-header">
        <div class="site-utility">
            <div class="site-top-inner">
                <div class="utility-left">
                    <a href="{{ route('home') }}#how-it-works" class="utility-link">How it Works</a>
                    <span class="utility-divider">|</span>
                    <form action="{{ route('products.index') }}" method="GET" class="utility-search-form" role="search">
                        <button class="utility-search-icon" type="submit" aria-label="Search">
                            <i class="bi bi-search"></i>
                        </button>
                        <input
                            type="text"
                            name="search"
                            class="utility-search-input"
                            placeholder="Search item, trend or occasion"
                            value="{{ request('search') }}"
                        >
                    </form>
                </div>

                <a class="top-wordmark" href="{{ route('home') }}" aria-label="Rentique Home">
                    RENTI<span class="logo-q">Q</span>UE
                </a>

                <div class="utility-right">
                    <a href="#" class="icon-action" aria-label="Wishlist">
                        <i class="bi bi-heart"></i>
                        @if(($wishlistCount ?? 0) > 0)
                            <span class="icon-badge">{{ ($wishlistCount ?? 0) > 99 ? '99+' : ($wishlistCount ?? 0) }}</span>
                        @endif
                    </a>

                    <a href="{{ route('cart.index') }}" class="icon-action" aria-label="Cart">
                        <i class="bi bi-cart3"></i>
                        @if(($cartBadgeCount ?? 0) > 0)
                            <span class="icon-badge">{{ $cartBadgeCount > 99 ? '99+' : $cartBadgeCount }}</span>
                        @endif
                    </a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-login">Dashboard</a>
                        @elseif(Auth::user()->isSeller())
                            <a href="{{ route('seller.dashboard') }}" class="btn-login">Dashboard</a>
                        @else
                            <a href="{{ route('profile') }}" class="btn-login">Profile</a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-signup">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-signup">Join Now</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="site-category-row">
            <div class="site-bottom-inner">
                <nav class="main-nav-links" aria-label="Primary">
                    <a href="{{ route('products.index') }}" class="main-nav-link">Browse All</a>

                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="main-nav-link">New Arrivals</a>

                    <div class="dropdown">
                        <a href="#" class="main-nav-link dropdown-toggle" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                        <ul class="dropdown-menu main-nav-dropdown-menu" aria-labelledby="categoriesDropdown">
                            @php
                                $navCategories = \App\Models\Category::where('is_approved', true)->orderBy('name')->get();
                                if ($navCategories->isEmpty()) {
                                    $navCategories = \App\Models\Category::orderBy('name')->get();
                                }
                            @endphp
                            <li><a class="dropdown-item" href="{{ route('products.index') }}">All Categories</a></li>
                            @if($navCategories->isNotEmpty())
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            @forelse($navCategories as $navCategory)
                                <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $navCategory->name]) }}">{{ $navCategory->name }}</a></li>
                            @empty
                                <li><span class="dropdown-item-text">No categories available</span></li>
                            @endforelse
                        </ul>
                    </div>

                    <a href="{{ route('products.index', ['search' => 'wedding']) }}" class="main-nav-link">Weddings</a>
                    <a href="{{ route('products.index', ['sort' => 'price_asc']) }}" class="main-nav-link is-accent">Clearance</a>
                </nav>
            </div>
        </div>
    </header>

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
    <main class="app-main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <span>Ren</span><span>tique</span>
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
                <div><i class="bi bi-envelope"></i> info@rentique.bt</div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Rentique. All rights reserved.
            </div>
        </div>
    </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBackOrHome() {
            if (window.history.length > 1) {
                window.history.back();
                return;
            }
            window.location.href = "{{ route('home') }}";
        }

        // Force refresh when returning via browser back/forward cache.
        window.addEventListener('pageshow', function (event) {
            const navigationEntries = performance.getEntriesByType('navigation');
            const isBackForward = navigationEntries.length > 0 && navigationEntries[0].type === 'back_forward';

            if (event.persisted || isBackForward) {
                window.location.reload();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
