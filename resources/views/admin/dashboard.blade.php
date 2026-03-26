@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .admin-dash {
        background:
            radial-gradient(circle at 14% 18%, rgba(191, 153, 94, 0.09), transparent 36%),
            radial-gradient(circle at 88% 8%, rgba(72, 114, 90, 0.08), transparent 36%),
            linear-gradient(180deg, #f7f3ed 0%, #f2ebe0 100%);
        border-radius: 14px;
        padding: 1.15rem;
    }

    .admin-title {
        font-size: 2rem;
        color: #2f2a23;
        margin-bottom: 0.2rem;
    }

    .admin-sub {
        color: #6f6454;
        margin-bottom: 1.25rem;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.9rem;
        margin-bottom: 0.9rem;
    }

    .kpi {
        border-radius: 12px;
        color: #fff;
        padding: 1rem;
        min-height: 108px;
        box-shadow: 0 8px 16px rgba(34, 34, 34, 0.12);
        position: relative;
        overflow: hidden;
    }

    .kpi::after {
        content: '';
        position: absolute;
        right: -12px;
        bottom: -18px;
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
    }

    .kpi-label { font-size: 0.9rem; opacity: 0.95; }
    .kpi-value { font-size: 2rem; line-height: 1; font-weight: 700; margin-top: 0.35rem; }
    .kpi-note { font-size: 0.8rem; margin-top: 0.35rem; opacity: 0.9; }
    .kpi-icon { position: absolute; right: 0.7rem; top: 0.7rem; font-size: 1.7rem; opacity: 0.78; }

    .kpi-blue { background: linear-gradient(130deg, #3f71db, #274f9f); }
    .kpi-green { background: linear-gradient(130deg, #4d8a52, #326938); }
    .kpi-gold { background: linear-gradient(130deg, #dca129, #ad770d); }
    .kpi-teal { background: linear-gradient(130deg, #407d72, #285c54); }

    .dash-panel {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(149, 129, 101, 0.2);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(45, 38, 27, 0.1);
        backdrop-filter: blur(1.5px);
        height: 100%;
    }

    .dash-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.7rem;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid rgba(142, 121, 91, 0.18);
    }

    .dash-panel-title {
        font-size: 1.25rem;
        color: #342d24;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .dash-panel-body { padding: 0.9rem 1rem 1rem; }

    .table-admin th,
    .table-admin td {
        border-color: rgba(148, 128, 100, 0.2);
        white-space: nowrap;
        font-size: 0.91rem;
    }

    .table-admin th {
        background: rgba(244, 235, 222, 0.58);
        color: #685b49;
        font-weight: 600;
    }

    .badge-soft {
        border-radius: 999px;
        padding: 0.2rem 0.6rem;
        font-size: 0.76rem;
        font-weight: 600;
    }

    .badge-soft-warn { background: #f6e7bf; color: #6d4f16; }
    .badge-soft-ok { background: #d6efda; color: #22502c; }
    .badge-soft-blue { background: #d8e7ff; color: #214b8e; }

    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.65rem;
    }

    .summary-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px dashed rgba(121, 102, 76, 0.24);
        padding-bottom: 0.5rem;
        color: #5a4d3b;
    }

    .summary-list strong { color: #2f281f; }

    .trend-box {
        margin-top: 0.9rem;
        border-radius: 10px;
        padding: 0.5rem;
        border: 1px solid rgba(148, 129, 102, 0.2);
        background: linear-gradient(180deg, #f5ecdc, #efe6d8);
    }

    .trend-axis {
        display: flex;
        justify-content: space-between;
        font-size: 0.74rem;
        color: #786854;
        padding: 0 0.25rem;
    }

    @media (max-width: 1199px) {
        .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 767px) {
        .kpi-grid { grid-template-columns: 1fr; }
        .admin-title { font-size: 1.55rem; }
    }
</style>
@endpush

@section('content')
<div class="container py-3 py-md-4">
    <section class="admin-dash">
        <h1 class="admin-title">Admin Dashboard</h1>
        <p class="admin-sub">Control approvals, orders, and marketplace performance from one place.</p>

        <div class="kpi-grid">
            <article class="kpi kpi-blue">
                <i class="bi bi-people kpi-icon"></i>
                <div class="kpi-label">Total Customers</div>
                <div class="kpi-value">{{ $totalUsers }}</div>
                <div class="kpi-note">Marketplace members</div>
            </article>

            <article class="kpi kpi-green">
                <i class="bi bi-shop kpi-icon"></i>
                <div class="kpi-label">Total Sellers</div>
                <div class="kpi-value">{{ $totalSellers }}</div>
                <div class="kpi-note">Verified + pending</div>
            </article>

            <article class="kpi kpi-gold">
                <i class="bi bi-person-check kpi-icon"></i>
                <div class="kpi-label">Pending Seller Approvals</div>
                <div class="kpi-value">{{ $pendingSellers }}</div>
                <div class="kpi-note">Needs review</div>
            </article>

            <article class="kpi kpi-teal">
                <i class="bi bi-cash-coin kpi-icon"></i>
                <div class="kpi-label">Active Rentals</div>
                <div class="kpi-value">{{ $activeRentals ?? 0 }}</div>
                <div class="kpi-note">+ Nu. {{ number_format($weeklyRevenue ?? 0, 0) }} this week</div>
            </article>
        </div>

        <div class="row g-3">
            <div class="col-xl-7">
                <section class="dash-panel">
                    <div class="dash-panel-head">
                        <h2 class="dash-panel-title"><i class="bi bi-person-lines-fill"></i> Seller Approvals</h2>
                        <a href="{{ route('admin.sellers') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="dash-panel-body">
                        <div class="table-responsive">
                            <table class="table table-admin align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Seller</th>
                                        <th>Shop Name</th>
                                        <th>Location</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingSellerList as $seller)
                                        <tr>
                                            <td>{{ $seller->user->name }}</td>
                                            <td>{{ $seller->shop_name }}</td>
                                            <td>{{ $seller->address ?: 'N/A' }}</td>
                                            <td>{{ $seller->phone ?: ($seller->contact_number ?: 'N/A') }}</td>
                                            <td><span class="badge-soft badge-soft-warn">Pending</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No pending seller approvals.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-5">
                <section class="dash-panel">
                    <div class="dash-panel-head">
                        <h2 class="dash-panel-title"><i class="bi bi-box-seam"></i> Item Approvals</h2>
                        <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-secondary">Review</a>
                    </div>
                    <div class="dash-panel-body">
                        <div class="table-responsive">
                            <table class="table table-admin align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Pending Orders</th>
                                        <th>Price</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingProductList as $product)
                                        <tr>
                                            <td>{{ $product->seller->shop_name ?? $product->name }}</td>
                                            <td>Nu. {{ number_format($product->rental_price, 0) }}</td>
                                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted py-4">No pending products.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-8">
                <section class="dash-panel">
                    <div class="dash-panel-head">
                        <h2 class="dash-panel-title"><i class="bi bi-receipt-cutoff"></i> Orders & Rentals</h2>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary">View Orders</a>
                    </div>
                    <div class="dash-panel-body">
                        <div class="table-responsive">
                            <table class="table table-admin align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Item</th>
                                        <th>Seller</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                        @php
                                            $firstItem = $order->orderItems->first();
                                            $itemName = optional(optional($firstItem)->product)->name ?? 'N/A';
                                            $sellerName = optional(optional(optional($firstItem)->product)->seller)->shop_name ?? 'N/A';
                                        @endphp
                                        <tr>
                                            <td>#{{ $order->order_number ?: $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                            <td>{{ $itemName }}</td>
                                            <td>{{ $sellerName }}</td>
                                            <td>
                                                @if($order->status === 'completed')
                                                    <span class="badge-soft badge-soft-ok">Completed</span>
                                                @elseif($order->status === 'pending')
                                                    <span class="badge-soft badge-soft-warn">Pending</span>
                                                @else
                                                    <span class="badge-soft badge-soft-blue">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No recent orders.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-4">
                <section class="dash-panel">
                    <div class="dash-panel-head">
                        <h2 class="dash-panel-title"><i class="bi bi-graph-up-arrow"></i> Revenue & Commission</h2>
                    </div>
                    <div class="dash-panel-body">
                        <ul class="summary-list">
                            <li><span>Total Revenue</span><strong>Nu. {{ number_format($totalRevenue, 0) }}</strong></li>
                            <li><span>Platform Commission ({{ number_format($commissionRate, 2) }}%)</span><strong>Nu. {{ number_format($platformCommission, 0) }}</strong></li>
                            <li><span>This Week</span><strong>Nu. {{ number_format($weeklyRevenue ?? 0, 0) }}</strong></li>
                            <li><span>Seller Payout</span><strong>Nu. {{ number_format($sellerPayout, 0) }}</strong></li>
                            <li><span>Total Orders</span><strong>{{ $totalOrders }}</strong></li>
                            <li><span>Completed Orders</span><strong>{{ $completedOrders }}</strong></li>
                            <li><span>Pending Products</span><strong>{{ $pendingProducts }}</strong></li>
                        </ul>

                        <div class="trend-box" aria-hidden="true">
                            <svg viewBox="0 0 100 42" width="100%" height="90" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="admin-trend-fill" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#6f9f73" stop-opacity="0.42"></stop>
                                        <stop offset="100%" stop-color="#6f9f73" stop-opacity="0"></stop>
                                    </linearGradient>
                                </defs>
                                <path d="M4 37 L18 34 L38 29 L56 26 L76 20 L96 8 L96 38 L4 38 Z" fill="url(#admin-trend-fill)"></path>
                                <path d="M4 37 L18 34 L38 29 L56 26 L76 20 L96 8" fill="none" stroke="#5f8663" stroke-width="1.8"></path>
                                <circle cx="18" cy="34" r="1.5" fill="#d2a650"></circle>
                                <circle cx="56" cy="26" r="1.5" fill="#d2a650"></circle>
                                <circle cx="96" cy="8" r="2" fill="#d2a650"></circle>
                            </svg>
                            <div class="trend-axis">
                                <span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline-primary">Manage Categories</a>
                            <a href="{{ route('admin.settings') }}" class="btn btn-sm btn-primary">Platform Settings</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
@endsection
