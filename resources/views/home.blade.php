@extends('layouts.app')

@section('title', 'Rentique - Rent Traditional Bhutanese Attire')

@push('styles')
<style>
    .hero-section {
        position: relative;
        overflow: hidden;
        min-height: 620px;
        background: none;
    }

    .hero-media {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .hero-carousel,
    .hero-carousel .carousel-inner,
    .hero-carousel .carousel-item {
        height: 100%;
    }

    .hero-carousel .carousel-item {
        position: relative;
        background: #0f1718;
    }

    .hero-carousel .carousel-item::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(100deg, rgba(0, 0, 0, 0.58) 0%, rgba(0, 0, 0, 0.35) 40%, rgba(0, 0, 0, 0.22) 100%);
        z-index: 1;
    }

    .hero-carousel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        padding: 0;
        transform-origin: center;
        will-change: transform, opacity;
    }

    .hero-carousel .carousel-item.active img {
        animation-name: heroEmerge;
        animation-duration: 4.8s;
        animation-timing-function: cubic-bezier(0.16, 0.84, 0.44, 1);
        animation-fill-mode: both;
    }

    @keyframes heroEmerge {
        0% {
            transform: scale(1.35);
            opacity: 0.8;
            filter: blur(1.6px);
        }
        55% {
            transform: scale(1.14);
            opacity: 0.95;
            filter: blur(0.5px);
        }
        100% {
            transform: scale(1);
            opacity: 1;
            filter: blur(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-carousel .carousel-item.active img {
            animation: none;
        }
    }

    .hero-content-wrap {
        position: relative;
        z-index: 2;
        min-height: 620px;
        display: flex;
        align-items: center;
    }

    .hero-carousel .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        width: 52px;
        opacity: 0.95;
    }

    .hero-carousel .carousel-control-prev-icon,
    .hero-carousel .carousel-control-next-icon {
        width: 2.1rem;
        height: 2.1rem;
        background-color: rgba(0, 0, 0, 0.35);
        border-radius: 999px;
        background-size: 55% 55%;
    }

    @media (max-width: 768px) {
        .hero-section,
        .hero-content-wrap {
            min-height: 520px;
        }

        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            width: 42px;
        }
    }

    .why-choose-image {
        position: relative;
        overflow: hidden;
    }

    .about-carousel,
    .about-carousel .carousel-inner,
    .about-carousel .carousel-item {
        height: 100%;
        min-height: 400px;
    }

    .about-carousel .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .about-carousel .carousel-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .about-carousel .carousel-control-prev,
    .about-carousel .carousel-control-next {
        width: 44px;
    }

    .about-carousel .carousel-control-prev-icon,
    .about-carousel .carousel-control-next-icon {
        width: 1.8rem;
        height: 1.8rem;
        background-color: rgba(0, 0, 0, 0.35);
        border-radius: 999px;
        background-size: 58% 58%;
    }
    
    .work-step-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: all 0.3s ease;
    }
    
    .work-step-link:hover {
        transform: translateY(-5px);
    }
    
    .work-step-link:hover .work-step {
        color: #333;
    }
    
    .work-step-link:hover .work-icon {
        background-color: #660018;
        color: white;
    }
    
    .work-step {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .work-icon {
        transition: all 0.3s ease;
    }

</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-media" aria-hidden="true">
        <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="4200" data-bs-pause="hover" data-bs-touch="true">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/kira2.jpg') }}" alt="Traditional kira outfit">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/kishuthara.png') }}" alt="Kishuthara collection">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/gho3.png') }}" alt="Prom dress highlight">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/wedding gho.png') }}" alt="Wedding dress highlight">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous slide">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next slide">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <div class="container hero-content-wrap">
        <div class="row w-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1>Rent Authentic<br>Bhutanese Attire<br>for Every <span>Special Occasion</span></h1>
                    <p>Affordable clothes and bags for weddings, festivals, and special events.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.index') }}" class="btn-browse">
                            Browse Attire <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('products.index') }}" class="btn-rent">
                            Rent Now <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <div class="row">
            <div class="col-md-4">
                <a href="{{ route('products.index') }}" class="work-step-link">
                    <div class="work-step">
                        <div class="work-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3>Browse</h3>
                        <p>Explore Gho, Kira, Kabney, Rachu and accessories</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('products.index') }}" class="work-step-link">
                    <div class="work-step">
                        <div class="work-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3>Rent</h3>
                        <p>Choose your size and rental duration</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                @auth
                    <a href="{{ route('orders.index') }}" class="work-step-link">
                @else
                    <a href="{{ route('products.index') }}" class="work-step-link">
                @endauth
                    <div class="work-step">
                        <div class="work-icon">
                            <i class="bi bi-arrow-clockwise"></i>
                        </div>
                        <h3>Return</h3>
                        <p>Return the attire after your event</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Attire Section -->
<section class="featured-attire">
    <div class="container">
        <h2 class="section-title">Featured Attire</h2>
        <div class="row">
            @forelse($products->take(4) as $product)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card">
                        @if($product->images && count($product->images) > 0)
                            <a href="{{ route('products.show', $product->id) }}" class="d-block" aria-label="View details for {{ $product->name }}">
                                <img src="{{ asset('storage/' . $product->images[0]) }}" class="card-img-top" alt="{{ $product->name }}">
                            </a>
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #e8e3dc 0%, #d4cfc4 100%);">
                                <i class="bi bi-image" style="font-size: 3rem; color: #999;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p class="price">Nu. {{ number_format($product->rental_price, 0) }} / day</p>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Default Featured Items -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card">
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);">
                            <i class="bi bi-star" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="card-body">
                            <h5>Men's Gho</h5>
                            <p class="price">Nu. 400 / day</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card">
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #FF6B9D 0%, #FFA07A 100%);">
                            <i class="bi bi-star" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="card-body">
                            <h5>Women's Kira</h5>
                            <p class="price">Nu. 450 / day</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card">
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #DC143C 0%, #FFD700 100%);">
                            <i class="bi bi-star" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="card-body">
                            <h5>Kabney</h5>
                            <p class="price">Nu. 100 / day</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card">
                        <div class="d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #800020 0%, #A0003A 100%);">
                            <i class="bi bi-star" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <div class="card-body">
                            <h5>Toego Jacket</h5>
                            <p class="price">Nu. 250 / day</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn-view-all">
                View All Attire <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Rentique Section -->
<section class="why-choose" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="why-choose-content">
                    <h2>Ready to Wear<br>Bhutanese <span>Tradition?</span></h2>
                    
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Affordable rental prices</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Easy booking system</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>High quality traditional attire</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Cleaned and ready-to-wear outfits</span>
                    </div>
                    
                    <div class="testimonial-box">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar"></div>
                            <div>
                                <div class="testimonial-name">Sonam, Thimphu</div>
                                <div class="testimonial-location">Thimphu, Drukpa</div>
                            </div>
                        </div>
                        <p class="testimonial-text">
                            "Rentique made it easy for me to rent a Gho for my cousin's wedding."
                        </p>
                        <div class="testimonial-meta">
                            <span><i class="bi bi-eye"></i> 1975-421-7898</span>
                            <span><i class="bi bi-info-circle"></i> info@rentique.XXXX</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('products.index') }}" class="btn-browse-attire">
                        Browse Attire <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="why-choose-image">
                    <div id="aboutImageCarousel" class="carousel slide carousel-fade about-carousel" data-bs-ride="carousel" data-bs-interval="3200" data-bs-pause="hover" data-bs-touch="true">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#aboutImageCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="About slide 1"></button>
                            <button type="button" data-bs-target="#aboutImageCarousel" data-bs-slide-to="1" aria-label="About slide 2"></button>
                            <button type="button" data-bs-target="#aboutImageCarousel" data-bs-slide-to="2" aria-label="About slide 3"></button>
                        </div>

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/kira2.jpg') }}" alt="Bhutanese kira attire">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/kishuthara.png') }}" alt="Colorful woven textile">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/wedding gho.png') }}" alt="Traditional wedding gho attire">
                            </div>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#aboutImageCarousel" data-bs-slide="prev" aria-label="Previous slide">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#aboutImageCarousel" data-bs-slide="next" aria-label="Next slide">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="faqs-section" id="faqs" style="padding: 4rem 0; background-color: #fff;">
    <div class="container">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item" style="border: none; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="border-radius: 8px; font-weight: 500;">
                                How do I rent traditional attire?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Browse our collection, select your desired attire, choose your size and rental duration, then proceed to checkout. We'll deliver the attire cleaned and ready to wear.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" style="border: none; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="border-radius: 8px; font-weight: 500;">
                                What is the rental period?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can rent attire for as short as one day or as long as you need. Pricing is per day, and you can specify your rental dates during checkout.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" style="border: none; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="border-radius: 8px; font-weight: 500;">
                                How do I return the attire?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Simply return the attire after your event. No need to clean it - we handle all cleaning. Just make sure to return it in good condition on the agreed date.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" style="border: none; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="border-radius: 8px; font-weight: 500;">
                                What if the attire doesn't fit?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We provide detailed size guides for all our attire. If you're unsure about sizing, contact us and we'll help you choose the right size for a perfect fit.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section" id="contact" style="padding: 4rem 0; background-color: #f9f9f9;">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-telephone" style="font-size: 1.5rem; color: #A0003A; margin-right: 1rem;"></i>
                                    <div>
                                        <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Phone</h5>
                                        <p style="color: #666; margin: 0;">+975-443-7890</p>
                                        <p style="color: #666; margin: 0;">+975-421-7898</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-envelope" style="font-size: 1.5rem; color: #A0003A; margin-right: 1rem;"></i>
                                    <div>
                                        <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Email</h5>
                                        <p style="color: #666; margin: 0;">info@rentique.bt</p>
                                        <p style="color: #666; margin: 0;">support@rentique.bt</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt" style="font-size: 1.5rem; color: #A0003A; margin-right: 1rem;"></i>
                                    <div>
                                        <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Address</h5>
                                        <p style="color: #666; margin: 0;">Thimphu, Bhutan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-clock" style="font-size: 1.5rem; color: #A0003A; margin-right: 1rem;"></i>
                                    <div>
                                        <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Hours</h5>
                                        <p style="color: #666; margin: 0;">Mon - Sat: 9:00 AM - 6:00 PM</p>
                                        <p style="color: #666; margin: 0;">Sunday: Closed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-lg" style="background-color: #800020; color: #FFFFFF; padding: 0.8rem 2.5rem; border-radius: 5px; text-decoration: none;">
                                Start Browsing <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
