@extends('seller.layouts.app')

@section('title', 'Process Return')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Process Return - Order #{{ $orderItem->order_id }}</h5>
                    <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Orders
                    </a>
                </div>
                <div class="card-body">
                    <!-- Order Details -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Product Details</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    @if($orderItem->product->primary_image_url)
                                        <img src="{{ $orderItem->product->primary_image_url }}" 
                                             class="img-fluid" alt="{{ $orderItem->product->name }}">
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h5>{{ $orderItem->product->name }}</h5>
                                    <p class="mb-1"><strong>Customer:</strong> {{ $orderItem->order->user->name }}</p>
                                    <p class="mb-1"><strong>Quantity:</strong> {{ $orderItem->quantity }}</p>
                                    <p class="mb-1"><strong>Rental Period:</strong> 
                                        {{ \Carbon\Carbon::parse($orderItem->rental_start_date)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($orderItem->rental_end_date)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Form -->
                    <form action="{{ route('seller.orders.return.process', $orderItem->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="condition" class="form-label">Item Condition *</label>
                            <select class="form-select @error('condition') is-invalid @enderror" 
                                    id="condition" name="condition" required onchange="toggleDamageFields()">
                                <option value="">Select Condition</option>
                                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good (No Damage)</option>
                                <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="damageFields" style="display: none;">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                <strong>Damage Detected:</strong> Please provide details about the damage.
                            </div>

                            <div class="mb-3">
                                <label for="damage_type" class="form-label">Damage Type *</label>
                                <select class="form-select @error('damage_type') is-invalid @enderror" 
                                        id="damage_type" name="damage_type">
                                    <option value="">Select Damage Type</option>
                                    <option value="minor_tear" {{ old('damage_type') == 'minor_tear' ? 'selected' : '' }}>
                                        Minor Tear (Nu. 50)
                                    </option>
                                    <option value="major_tear" {{ old('damage_type') == 'major_tear' ? 'selected' : '' }}>
                                        Major Tear (Nu. 150)
                                    </option>
                                    <option value="stain" {{ old('damage_type') == 'stain' ? 'selected' : '' }}>
                                        Stain (Nu. 100)
                                    </option>
                                    <option value="missing_accessory" {{ old('damage_type') == 'missing_accessory' ? 'selected' : '' }}>
                                        Missing Accessory (Nu. 200)
                                    </option>
                                    <option value="other" {{ old('damage_type') == 'other' ? 'selected' : '' }}>
                                        Other (Nu. 100)
                                    </option>
                                </select>
                                @error('damage_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="damage_description" class="form-label">Damage Description *</label>
                                <textarea class="form-control @error('damage_description') is-invalid @enderror" 
                                          id="damage_description" name="damage_description" rows="4" 
                                          placeholder="Describe the damage in detail...">{{ old('damage_description') }}</textarea>
                                @error('damage_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Process Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleDamageFields() {
        const condition = document.getElementById('condition').value;
        const damageFields = document.getElementById('damageFields');
        
        if (condition === 'damaged') {
            damageFields.style.display = 'block';
            document.getElementById('damage_type').required = true;
            document.getElementById('damage_description').required = true;
        } else {
            damageFields.style.display = 'none';
            document.getElementById('damage_type').required = false;
            document.getElementById('damage_description').required = false;
        }
    }

    // Check on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleDamageFields();
    });
</script>
@endpush
@endsection
