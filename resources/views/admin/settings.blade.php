@extends('layouts.app')

@section('title', 'Platform Settings')

@push('styles')
<style>
    .settings-wrap {
        max-width: 920px;
        margin: 0 auto;
    }

    .settings-card {
        border: 1px solid #e7ddcd;
        border-radius: 14px;
        background: linear-gradient(180deg, #fbf7ef 0%, #f3eadb 100%);
        box-shadow: 0 10px 24px rgba(56, 46, 30, 0.08);
        overflow: hidden;
    }

    .settings-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e6d9c7;
        background: rgba(255, 255, 255, 0.55);
    }

    .settings-title {
        margin: 0;
        font-size: 1.4rem;
        color: #2f2a23;
    }

    .settings-subtitle {
        margin-top: 0.3rem;
        margin-bottom: 0;
        color: #6e624f;
        font-size: 0.92rem;
    }

    .settings-body {
        padding: 1.25rem;
    }

    .settings-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        border: 1px solid #e9dcc9;
        border-radius: 10px;
        background: #fff;
        padding: 0.75rem 0.85rem;
    }

    .meta-label {
        font-size: 0.82rem;
        color: #766854;
        margin-bottom: 0.2rem;
    }

    .meta-value {
        font-weight: 700;
        color: #2d271f;
        font-size: 1.05rem;
    }

    @media (max-width: 767px) {
        .settings-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="settings-wrap">
        <div class="settings-card">
            <div class="settings-header">
                <h1 class="settings-title">Platform Settings</h1>
                <p class="settings-subtitle">Manage commission and payout configuration for all rental transactions.</p>
            </div>

            <div class="settings-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="settings-meta">
                    <div class="meta-item">
                        <div class="meta-label">Current Commission Rate</div>
                        <div class="meta-value">{{ number_format((float) $commissionRate, 2) }}%</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Seller Payout Share</div>
                        <div class="meta-value">{{ number_format(100 - (float) $commissionRate, 2) }}%</div>
                    </div>
                </div>

                <form action="{{ route('admin.settings.update') }}" method="POST" class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-8">
                        <label for="commission_rate" class="form-label fw-semibold">Commission Rate (%)</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            id="commission_rate"
                            name="commission_rate"
                            class="form-control"
                            value="{{ old('commission_rate', $commissionRate) }}"
                            required
                        >
                        <div class="form-text">Set how much the platform deducts from each paid order.</div>
                    </div>

                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>

                    <div class="col-12">
                        <div class="form-check mt-1">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                id="refund_platform_fee"
                                name="refund_platform_fee"
                                {{ old('refund_platform_fee', $refundPlatformFee ? '1' : '0') === '1' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="refund_platform_fee">
                                Refund platform fee on cancellation
                            </label>
                            <div class="form-text">When unchecked, platform fee stays non-refundable and is excluded from refund amount.</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
