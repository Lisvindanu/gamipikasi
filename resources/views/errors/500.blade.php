<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | GDGoC Gamification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wgt@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Google Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-container {
            text-align: center;
            color: white;
            max-width: 600px;
        }

        .error-code {
            font-size: 10rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 4px 4px 8px rgba(0,0,0,0.3);
            animation: glitch 3s ease-in-out infinite;
        }

        @keyframes glitch {
            0%, 90%, 100% { transform: translate(0); }
            92% { transform: translate(-2px, 2px); }
            94% { transform: translate(2px, -2px); }
            96% { transform: translate(-2px, -2px); }
            98% { transform: translate(2px, 2px); }
        }

        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            background: white;
            color: #fa709a;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .illustration {
            font-size: 6rem;
            margin-bottom: 2rem;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.5; }
        }

        @media (max-width: 640px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.75rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .illustration {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="illustration">⚠️</div>
        <div class="error-code">500</div>
        <h1 class="error-title">Internal Server Error</h1>
        <p class="error-message">
            Oops! Something went wrong on our end. Our team has been notified and we're working to fix the issue.
        </p>
        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                ← Go Back
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary">
                🏠 Go Home
            </a>
        </div>
    </div>
</body>
</html>
