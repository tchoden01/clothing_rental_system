<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome! - Clothing Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .completion-container {
            background: white;
            padding: 80px 60px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 600px;
        }

        .checkmark {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: #4caf50;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }

        .checkmark svg {
            width: 60px;
            height: 60px;
            stroke: white;
            stroke-width: 4;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: draw 0.5s ease-out 0.3s forwards;
        }

        @keyframes draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        h1 {
            font-size: 42px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .cta-btn {
            background-color: #764ba2;
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cta-btn:hover {
            background-color: #667eea;
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <div class="completion-container">
        <div class="checkmark">
            <svg viewBox="0 0 52 52">
                <polyline points="14,27 22,35 38,17"/>
            </svg>
        </div>

        <h1>You're All Set!</h1>
        
        <p>
            Welcome to your clothing rental journey! Your account is ready, and you can start browsing our collection. 
            Let's find your perfect styles!
        </p>

        <a href="{{ route('products.index') }}" class="cta-btn">Start Shopping</a>
    </div>
</body>
</html>
