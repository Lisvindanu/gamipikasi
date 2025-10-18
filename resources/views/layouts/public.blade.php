<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GDGoC Gamification') - Universitas Pasundan</title>
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
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
