<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Get Started - Clothing Rental</title>
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
            display: flex;
            flex-direction: column;
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
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
            line-height: 1;
        }

        .close-btn:hover {
            color: #333;
        }

        .onboarding-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            text-align: center;
        }

        .illustration {
            width: 180px;
            height: 180px;
            margin-bottom: 40px;
        }

        .illustration svg {
            width: 100%;
            height: 100%;
        }

        h1 {
            font-size: 42px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
        }

        .description {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            max-width: 600px;
            margin-bottom: 50px;
        }

        .next-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 18px 80px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .next-btn:hover {
            background-color: #c9302c;
            transform: translateY(-2px);
        }

        .signin-link {
            margin-top: 30px;
            font-size: 15px;
            color: #666;
        }

        .signin-link a {
            color: #d9534f;
            text-decoration: none;
            font-weight: 600;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="onboarding-header">
        <a href="{{ route('home') }}" class="logo">nuuly</a>
        <div class="header-steps">
            <div class="step-item active">
                <div class="step-counter">0 / 6</div>
                <div>CREATE AN ACCOUNT</div>
            </div>
            <div class="step-item">
                <div class="step-counter">0 / 4</div>
                <div>SUBSCRIBE</div>
            </div>
            <div class="step-item">
                <div class="step-counter">0 / 5</div>
                <div>BUILD YOUR PROFILE</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="close-btn">&times;</a>
    </div>

    <div class="onboarding-content">
        <div class="illustration">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <!-- Suitcase/box illustration -->
                <rect x="40" y="70" width="120" height="90" fill="#e8f5e9" stroke="#333" stroke-width="2" rx="5"/>
                <rect x="45" y="75" width="110" height="80" fill="#c8e6c9" stroke="#333" stroke-width="1.5"/>
                
                <!-- Clothes hanging -->
                <line x1="70" y1="55" x2="70" y2="75" stroke="#333" stroke-width="2"/>
                <rect x="60" y="75" width="20" height="35" fill="#a5d6a7" stroke="#333" stroke-width="1.5" rx="2"/>
                
                <line x1="100" y1="50" x2="100" y2="75" stroke="#333" stroke-width="2"/>
                <rect x="90" y="75" width="20" height="38" fill="#81c784" stroke="#333" stroke-width="1.5" rx="2"/>
                
                <line x1="130" y1="58" x2="130" y2="75" stroke="#333" stroke-width="2"/>
                <rect x="120" y="75" width="20" height="32" fill="#66bb6a" stroke="#333" stroke-width="1.5" rx="2"/>
                
                <!-- Handle -->
                <path d="M 85 65 Q 100 50 115 65" fill="none" stroke="#333" stroke-width="2.5" stroke-linecap="round"/>
                
                <!-- Box details -->
                <line x1="70" y1="95" x2="130" y2="95" stroke="#4caf50" stroke-width="1.5"/>
                <line x1="70" y1="110" x2="130" y2="110" stroke="#4caf50" stroke-width="1.5"/>
                <line x1="70" y1="125" x2="130" y2="125" stroke="#4caf50" stroke-width="1.5"/>
            </svg>
        </div>

        <h1>Let's Get This Party Started</h1>
        
        <p class="description">
            We'll get you set up with an account ASAP. Answering a few quick questions will take you right to the fun stuff—picking out your first 6 styles
        </p>

        <a href="{{ route('onboarding.create-account') }}" class="next-btn">Next</a>

        <div class="signin-link">
            Already have a Nuuly account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</body>
</html>
