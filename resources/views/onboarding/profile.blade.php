<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Your Profile - Clothing Rental</title>
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
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container {
            background: white;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 16px;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        select, input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #d9534f;
        }

        .submit-btn {
            width: 100%;
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: #c9302c;
        }

        .skip-btn {
            width: 100%;
            background-color: transparent;
            color: #666;
            border: 2px solid #e0e0e0;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .skip-btn:hover {
            border-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="onboarding-header">
        <a href="{{ route('home') }}" class="logo">nuuly</a>
        <div class="header-steps">
            <div class="step-item">
                <div class="step-counter">6 / 6</div>
                <div>CREATE AN ACCOUNT</div>
            </div>
            <div class="step-item">
                <div class="step-counter">4 / 4</div>
                <div>SUBSCRIBE</div>
            </div>
            <div class="step-item active">
                <div class="step-counter">1 / 5</div>
                <div>BUILD YOUR PROFILE</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="close-btn">&times;</a>
    </div>

    <div class="content-wrapper">
        <div class="form-container">
            <h1>Tell Us About Your Style</h1>
            <p class="subtitle">This helps us recommend the perfect items for you</p>

            <form action="{{ route('onboarding.store-profile') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="size_preference">Preferred Size</label>
                    <select id="size_preference" name="size_preference">
                        <option value="">Select a size</option>
                        <option value="XS">Extra Small (XS)</option>
                        <option value="S">Small (S)</option>
                        <option value="M">Medium (M)</option>
                        <option value="L">Large (L)</option>
                        <option value="XL">Extra Large (XL)</option>
                        <option value="XXL">2XL</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="style_preference">Style Preferences</label>
                    <input type="text" id="style_preference" name="style_preference" placeholder="e.g., Casual, Business, Bohemian">
                </div>

                <button type="submit" class="submit-btn">Complete Setup</button>
                <button type="submit" class="skip-btn">Skip for Now</button>
            </form>
        </div>
    </div>
</body>
</html>
