<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Plan - Clothing Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f8f8;
            min-height: 100vh;
        }

        .onboarding-header {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            text-decoration: none;
        }

        .header-steps {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ccc;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .step-item.active {
            color: #d9534f;
        }

        .step-counter {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .close-btn {
            font-size: 30px;
            color: #ccc;
            text-decoration: none;
        }

        .content-wrapper {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 18px;
            margin-bottom: 50px;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .plan-card {
            background: white;
            border-radius: 12px;
            padding: 40px 25px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .plan-card input[type="radio"] {
            display: none;
        }

        .plan-card input[type="radio"]:checked + .plan-content {
            border-color: #d9534f;
        }

        .plan-card.selected {
            border-color: #d9534f;
        }

        .plan-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: #d9534f;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .plan-name {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }

        .plan-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        .plan-price {
            font-size: 32px;
            font-weight: 700;
            color: #d9534f;
            margin-bottom: 8px;
        }

        .plan-price-detail {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .plan-features li {
            padding: 10px 0;
            color: #555;
            font-size: 15px;
        }

        .submit-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 16px 60px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 40px auto 0;
        }

        .submit-btn:hover {
            background-color: #c9302c;
        }

        .submit-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
    <script>
        function selectPlan(planId) {
            document.querySelectorAll('.plan-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('submit-btn').disabled = false;
        }
    </script>
</head>
<body>
    <div class="onboarding-header">
        <a href="{{ route('home') }}" class="logo">nuuly</a>
        <div class="header-steps">
            <div class="step-item">
                <div class="step-counter">6 / 6</div>
                <div>CREATE AN ACCOUNT</div>
            </div>
            <div class="step-item active">
                <div class="step-counter">1 / 4</div>
                <div>SUBSCRIBE</div>
            </div>
            <div class="step-item">
                <div class="step-counter">0 / 5</div>
                <div>BUILD YOUR PROFILE</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="close-btn">&times;</a>
    </div>

    <div class="content-wrapper">
        <h1>Choose Your Plan</h1>
        <p class="subtitle">All plans include free shipping both ways and flexible swaps</p>

        <form action="{{ route('onboarding.store-subscription') }}" method="POST">
            @csrf
            <div class="plans-grid">
                @foreach($plans as $index => $plan)
                    <label class="plan-card" onclick="selectPlan({{ $plan->id }})">
                        @if($index == 2)
                            <div class="plan-badge">Best Value</div>
                        @endif
                        
                        <input type="radio" name="plan_id" value="{{ $plan->id }}" required>
                        
                        <div class="plan-content">
                            <div class="plan-name">
                                @if($index == 0)
                                    THE WARDROBE PICK-ME-UP
                                @elseif($index == 1)
                                    THE WARDROBE ENHANCER
                                @else
                                    THE WARDROBE REPLACER
                                @endif
                            </div>
                            
                            <div class="plan-title">{{ $plan->name }}</div>
                            
                            <div class="plan-price">
                                ${{ $plan->first_month_price ? number_format($plan->first_month_price, 0) : number_format($plan->price, 0) }}
                                <span style="font-size: 16px;">{{ $plan->first_month_price ? '/first month' : '/month' }}</span>
                            </div>
                            
                            @if($plan->first_month_price)
                                <div class="plan-price-detail">
                                    (${{ number_format($plan->price, 0) }}/month after)
                                </div>
                            @endif
                            
                            <ul class="plan-features">
                                <li>Rent {{ $plan->isUnlimited() ? '6 items at a time' : $plan->item_limit . ' items at a time' }}</li>
                                <li>
                                    @if($plan->isUnlimited())
                                        <strong>Unlimited</strong> Swaps
                                    @else
                                        Swap every {{ $plan->swap_days }} days
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </label>
                @endforeach
            </div>

            <button type="submit" id="submit-btn" class="submit-btn" disabled>Continue to Profile</button>
        </form>
    </div>
</body>
</html>
