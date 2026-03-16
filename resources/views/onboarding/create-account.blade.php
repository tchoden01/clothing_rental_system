<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Clothing Rental</title>
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
            text-decoration: none;
        }

        .onboarding-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }

        .form-container {
            background: white;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
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

        input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input:focus {
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

        .error {
            color: #d9534f;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="onboarding-header">
        <a href="{{ route('home') }}" class="logo">nuuly</a>
        <div class="header-steps">
            <div class="step-item active">
                <div class="step-counter">1 / 6</div>
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
        <div class="form-container">
            <h1>Create Your Account</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('onboarding.store-account') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="contact_number">Phone Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="submit-btn">Continue</button>
            </form>
        </div>
    </div>
</body>
</html>
