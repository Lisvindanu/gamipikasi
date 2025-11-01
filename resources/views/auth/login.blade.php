<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - GDGoC Gamification System">
    <title>Login - GDGoC Gamification</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-container {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--google-blue), var(--google-green));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 700;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fef1f1;
            color: var(--google-red);
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #f0f9f4;
            color: var(--google-green);
            border: 1px solid #c3e6cb;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .submit-wrapper {
            margin-top: 2rem;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--google-blue), var(--google-green));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(66, 133, 244, 0.4);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .login-footer-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <div class="login-logo">
                    🎯
                </div>
                <h1 class="login-title">GDGoC Gamification</h1>
                <p class="login-subtitle">Login to your account</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong> {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="input-group">
                    <label for="email" class="label-modern">
                        Email<span class="required">*</span>
                    </label>
                    <div class="input-box">
                        <i data-lucide="mail" class="input-icon-left"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input-modern"
                            placeholder="your@email.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group" style="margin-top: 1.5rem;">
                    <label for="password" class="label-modern">
                        Password<span class="required">*</span>
                    </label>
                    <div class="input-box">
                        <i data-lucide="lock" class="input-icon-left"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input-modern"
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                    @error('password')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <div class="submit-wrapper">
                    <button type="submit" class="btn-login">
                        <span>Login</span>
                        <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                    </button>
                </div>
            </form>

            <div class="login-footer">
                <p class="login-footer-text">
                    © 2025 Google Developer Groups on Campus - Universitas Pasundan
                </p>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h3 class="loading-title">Logging in...</h3>
            <p class="loading-text">Please wait</p>
        </div>
    </div>

    <script>
        if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }

        // Show loading on form submit
        document.getElementById('login-form').addEventListener('submit', function() {
            document.getElementById('loading-overlay').classList.add('active');
        });
    </script>
</body>
</html>
