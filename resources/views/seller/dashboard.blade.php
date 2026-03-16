@extends('seller.layouts.app')

@section('title', 'Seller Dashboard')

@push('styles')
<style>
    .seller-main {
        background:
            radial-gradient(circle at 15% 20%, rgba(202, 168, 111, 0.14), transparent 36%),
            radial-gradient(circle at 88% 12%, rgba(66, 108, 86, 0.12), transparent 40%),
            linear-gradient(180deg, #f8f3e8 0%, #eee2ce 100%);
    }

    .dash-title {
        font-size: 2rem;
        margin-bottom: 0.85rem;
        color: #2f2a24;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.9rem;
        margin-bottom: 1rem;
    }

    .stat-box {
        border-radius: 10px;
        color: #fff;
        padding: 0.8rem 0.95rem;
        min-height: 92px;
        box-shadow: 0 7px 16px rgba(34, 34, 34, 0.14);
        position: relative;
        overflow: hidden;
    }

    .stat-box::after {
        content: '';
        position: absolute;
        right: -12px;
        bottom: -16px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.14);
    }

    .stat-title { font-size: 0.86rem; opacity: 0.95; margin-bottom: 0.2rem; }
    .stat-value { font-size: 2rem; line-height: 1; font-weight: 700; }
    .stat-note { font-size: 0.78rem; margin-top: 0.25rem; opacity: 0.92; }

    .bg-blue { background: linear-gradient(130deg, #3f72de, #274ea0); }
    .bg-gold { background: linear-gradient(130deg, #deab2f, #b57f0f); }
    .bg-orange { background: linear-gradient(130deg, #e29d26, #c57a12); }
    .bg-teal { background: linear-gradient(130deg, #3f7f73, #2a6057); }

    .panel {
        border: 1px solid rgba(152, 131, 101, 0.22);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 5px 14px rgba(49, 41, 30, 0.1);
        height: 100%;
    }

    .panel-head {
        padding: 0.75rem 0.95rem;
        border-bottom: 1px solid rgba(145, 124, 93, 0.22);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-title {
        margin: 0;
        font-size: 1.1rem;
        color: #2f2921;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .panel-body { padding: 0.85rem 0.95rem; }

    .mini-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.45rem;
        margin-bottom: 0.6rem;
    }

    .mini-card {
        border: 1px solid rgba(151, 131, 102, 0.2);
        border-radius: 6px;
        background: #f7f2e8;
        padding: 0.55rem 0.6rem;
    }

    .mini-label { font-size: 0.8rem; color: #655846; }
    .mini-value { font-size: 1.8rem; line-height: 1; font-weight: 700; color: #2f2921; margin-top: 0.2rem; }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .btn-soft {
        background: #2f5f56;
        border-color: #2f5f56;
        color: #fff;
    }

    .btn-soft:hover { background: #254d46; border-color: #254d46; color: #fff; }

    .shop-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .shop-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(143, 122, 92, 0.2);
        padding: 0.62rem 0.1rem;
        color: #463c2f;
        gap: 0.8rem;
    }

    .label-with-icon {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .badge-pill {
        border-radius: 999px;
        padding: 0.22rem 0.62rem;
        font-size: 0.76rem;
        font-weight: 600;
    }

    .pill-verified { background: #d9efdd; color: #21522a; }
    .pill-pending { background: #f4e6c2; color: #6b4c14; }

    .returns-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(143, 122, 92, 0.2);
        padding: 0.55rem 0.1rem;
        gap: 0.7rem;
    }

    .returns-meta {
        color: #6f6250;
        font-size: 0.82rem;
    }

    .report-box {
        margin-top: 0.8rem;
        border: 1px solid rgba(146, 126, 97, 0.22);
        border-radius: 8px;
        background: linear-gradient(180deg, #f6eedf, #efe4d1);
        padding: 0.55rem;
    }

    .report-axis {
        display: flex;
        justify-content: space-between;
        font-size: 0.74rem;
        color: #7a6a54;
        margin-top: 0.15rem;
    }

    #customers-section,
    #insights-section {
        scroll-margin-top: 95px;
    }

    .focus-pulse {
        animation: focusPulse 1.2s ease;
    }

    @keyframes focusPulse {
        0% { transform: scale(1); box-shadow: 0 0 0 rgba(47, 95, 86, 0); }
        40% { transform: scale(1.01); box-shadow: 0 0 0 3px rgba(47, 95, 86, 0.16); }
        100% { transform: scale(1); box-shadow: 0 0 0 rgba(47, 95, 86, 0); }
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 767px) {
        .stats-grid { grid-template-columns: 1fr; }
        .mini-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<h1 class="dash-title">Seller Dashboard</h1>

@if(!$seller->is_verified)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        Your account is pending verification. Add-product actions will work after approval.
    </div>
@endif

<section class="stats-grid">
    <article class="stat-box bg-blue">
        <div class="stat-title">Total Products</div>
        <div class="stat-value">{{ $products }}</div>
    </article>
    <article class="stat-box bg-gold">
        <div class="stat-title">Pending Approval</div>
        <div class="stat-value">{{ $pendingProducts }}</div>
    </article>
    <article class="stat-box bg-orange">
        <div class="stat-title">Items Rented Out</div>
        <div class="stat-value">{{ $itemsRentedOut }}</div>
        <div class="stat-note">{{ $pendingReturns->count() }} pending returns</div>
    </article>
    <article class="stat-box bg-teal" id="earnings-section">
        <div class="stat-title">Total Earnings</div>
        <div class="stat-value">Nu. {{ number_format($totalEarnings, 0) }}</div>
        <div class="stat-note">+ Nu. {{ number_format($weeklyEarnings, 0) }} this week</div>
    </article>
</section>

<div class="row g-3">
    <div class="col-xl-6">
        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title"><i class="bi bi-box-seam"></i> Product Management</h2>
            </div>
            <div class="panel-body">
                <div class="mini-grid">
                    <div class="mini-card">
                        <div class="mini-label">Platform Commission</div>
                        <div class="mini-value">{{ number_format($commissionRate, 0) }}%</div>
                    </div>
                    <div class="mini-card">
                        <div class="mini-label">Add New Product</div>
                        <div class="mini-value">{{ $pendingProducts }}</div>
                    </div>
                    <div class="mini-card">
                        <div class="mini-label">Manage Returns</div>
                        <div class="mini-value">{{ $goodReturns + $damagedReturns }}</div>
                    </div>
                </div>
                <div class="action-row">
                    <a href="{{ route('seller.products') }}" class="btn btn-soft btn-sm">View Products</a>
                    @if($seller->is_verified)
                        <a href="{{ route('seller.products.create') }}" class="btn btn-success btn-sm">Add New Product</a>
                    @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Add New Product</button>
                    @endif
                    <a href="{{ route('seller.orders') }}" class="btn btn-soft btn-sm">Manage Returns</a>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xl-6">
        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title"><i class="bi bi-receipt"></i> Orders & Returns</h2>
            </div>
            <div class="panel-body">
                <div class="mini-grid">
                    <div class="mini-card">
                        <div class="mini-label">Pending Orders</div>
                        <div class="mini-value">{{ $pendingOrdersCount }}</div>
                    </div>
                    <div class="mini-card">
                        <div class="mini-label">Returns Handling</div>
                        <div class="mini-value">{{ $goodReturns + $damagedReturns }}</div>
                    </div>
                    <div class="mini-card">
                        <div class="mini-label">Revenue This Month</div>
                        <div class="mini-value">Nu. {{ number_format($currentMonthEarnings, 0) }}</div>
                    </div>
                </div>
                <div class="action-row">
                    <a href="{{ route('seller.orders') }}" class="btn btn-soft btn-sm">View Orders</a>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xl-6" id="customers-section">
        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title"><i class="bi bi-shop"></i> Shop Information</h2>
            </div>
            <div class="panel-body">
                <ul class="shop-list">
                    <li>
                        <span class="label-with-icon"><i class="bi bi-shop-window"></i> Shop</span>
                        <strong>{{ $seller->shop_name }}</strong>
                    </li>
                    <li>
                        <span class="label-with-icon"><i class="bi bi-telephone"></i> Contact Number</span>
                        <strong>{{ $seller->contact_number ?: 'N/A' }}</strong>
                    </li>
                    <li>
                        <span class="label-with-icon"><i class="bi bi-geo-alt"></i> Address</span>
                        <strong>{{ $seller->address ?: 'N/A' }}</strong>
                    </li>
                    <li>
                        <span class="label-with-icon"><i class="bi bi-patch-check"></i> Verification Status</span>
                        @if($seller->is_verified)
                            <span class="badge-pill pill-verified">Verified</span>
                        @else
                            <span class="badge-pill pill-pending">Pending</span>
                        @endif
                    </li>
                    <li>
                        <span class="label-with-icon"><i class="bi bi-cash"></i> Total Earnings</span>
                        <strong>Nu. {{ number_format($totalEarnings, 0) }}</strong>
                    </li>
                    <li>
                        <span class="label-with-icon"><i class="bi bi-wallet2"></i> Platform Fees Paid</span>
                        <strong>
                            Nu.
                            {{ number_format($commissionRate < 100 ? ($totalEarnings * $commissionRate) / (100 - $commissionRate) : 0, 0) }}
                        </strong>
                    </li>
                    <li>
                        <span class="label-with-icon"><i class="bi bi-piggy-bank"></i> Account Balance</span>
                        <strong>Nu. {{ number_format($totalEarnings, 0) }}</strong>
                    </li>
                </ul>
                <div class="action-row mt-2">
                    <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-sm">Settings</a>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xl-6" id="insights-section">
        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title"><i class="bi bi-arrow-repeat"></i> Returns Handling</h2>
                <a href="{{ route('seller.orders') }}" class="btn btn-soft btn-sm">Manage</a>
            </div>
            <div class="panel-body">
                @if($pendingReturns->count() > 0)
                    @foreach($pendingReturns as $return)
                        <div class="returns-item">
                            <div>
                                <div><strong>{{ $return->product->name ?? 'Product' }}</strong></div>
                                <div class="returns-meta">
                                    {{ $return->rental_start_date ? $return->rental_start_date->format('M d') : 'N/A' }}
                                    -
                                    {{ $return->rental_end_date ? $return->rental_end_date->format('M d') : 'N/A' }}
                                </div>
                            </div>
                            <a href="{{ route('seller.orders.return', $return->id) }}" class="btn btn-soft btn-sm">Manage</a>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">No pending returns.</p>
                @endif

                <div class="report-box mt-3">
                    <div class="small text-muted">Earnings & Performance</div>
                    <div class="small text-muted">These 3 months earnings: Nu. {{ number_format($totalEarnings, 0) }}</div>
                    <svg viewBox="0 0 100 30" width="100%" height="70" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M6 26 L24 24 L42 20 L60 17 L78 12 L95 6" fill="none" stroke="#4b7a61" stroke-width="1.8"></path>
                        <path d="M6 26 L24 24 L42 20 L60 17 L78 12 L95 6" fill="none" stroke="#d0a14f" stroke-width="0.8" stroke-dasharray="1.5 2.5"></path>
                    </svg>
                    <div class="report-axis"><span>Feb</span><span>Mar</span><span>Apr</span></div>
                </div>

                <div class="action-row mt-2">
                    <a href="{{ route('seller.orders') }}" class="btn btn-soft btn-sm">View Reports</a>
                </div>
            </div>
        </section>
    </div>
</div>

@if(!empty($focusSection))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = {
                customers: 'customers-section',
                insights: 'insights-section'
            };

            var sectionKey = @json($focusSection);
            var targetId = map[sectionKey] || null;

            if (!targetId) {
                return;
            }

            var target = document.getElementById(targetId);
            if (!target) {
                return;
            }

            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            target.classList.add('focus-pulse');

            setTimeout(function () {
                target.classList.remove('focus-pulse');
            }, 1400);
        });
    </script>
    @endpush
@endif
@endsection
