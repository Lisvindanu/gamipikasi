<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GDGoC Gamification') - Universitas Pasundan</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/public.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')
</head>
<body>
    @include('components.public.navbar')

    @yield('content')

    @include('components.public.footer')

    <script>
        // Safe Lucide initialization
        window.initLucideIcons = function() {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                try {
                    lucide.createIcons();
                } catch (e) {
                    console.warn('Lucide initialization error:', e);
                }
            }
        };

        // Initialize icons on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', window.initLucideIcons);
        } else {
            window.initLucideIcons();
        }
    </script>
    @stack('scripts')
</body>
</html>
