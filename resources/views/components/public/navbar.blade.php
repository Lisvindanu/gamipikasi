<style>
    .navbar-public {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        background: white;
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 0;
        z-index: 1000;
        width: 100%;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .navbar-logo {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
    }

    .navbar-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .navbar-menu {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .navbar-links {
        display: flex;
        gap: 1.5rem;
    }

    .navbar-link {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: color 0.3s;
    }

    .navbar-link:hover {
        color: var(--google-blue);
    }

    .navbar-user-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .navbar-dashboard-links {
        display: flex;
        gap: 0.5rem;
    }

    .navbar-user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        background: var(--bg-light);
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
    }

    .navbar-user-info:hover {
        background: rgba(66, 133, 244, 0.1);
    }

    .navbar-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        overflow: hidden;
    }

    .navbar-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        color: var(--text-primary);
    }

    /* Mobile Menu */
    .mobile-menu {
        display: none;
        position: fixed;
        top: 73px;
        left: 0;
        width: 100%;
        height: calc(100vh - 73px);
        background: white;
        z-index: 99;
        padding: 1.5rem;
        overflow-y: auto;
    }

    .mobile-menu.active {
        display: block;
    }

    .mobile-menu-section {
        margin-bottom: 2rem;
    }

    .mobile-menu-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .mobile-menu-links {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .mobile-menu-link {
        padding: 1rem;
        border-radius: 12px;
        background: var(--bg-light);
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .mobile-menu-link:active {
        background: rgba(66, 133, 244, 0.1);
    }

    .mobile-user-card {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .mobile-user-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: white;
        color: var(--google-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
        overflow: hidden;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .navbar-dashboard-links span {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .navbar-public {
            padding: 1rem;
        }

        .navbar-title {
            font-size: 1rem;
        }

        .navbar-menu,
        .navbar-user-section {
            display: none;
        }

        .navbar-toggle {
            display: block;
        }

        .navbar-logo {
            width: 36px;
            height: 36px;
            font-size: 1.125rem;
        }
    }
</style>

<nav class="navbar-public">
    <!-- Left Side: Logo + Brand -->
    <div class="navbar-brand">
        <div class="navbar-logo">🎯</div>
        <h1 class="navbar-title">GDGoC Gamification</h1>
    </div>

    <!-- Desktop Menu -->
    <div class="navbar-menu">
        <div class="navbar-links">
            <a href="{{ route('home') }}" class="navbar-link">Beranda</a>
            <a href="{{ route('public.leaderboard') }}" class="navbar-link">Papan Peringkat</a>
            <a href="{{ route('posts.index') }}" class="navbar-link">Postingan</a>
            <a href="{{ route('public.badges') }}" class="navbar-link">Lencana</a>
            <a href="{{ route('public.organization') }}" class="navbar-link">Tim Kami</a>
        </div>
    </div>

    <!-- Desktop User Section -->
    <div class="navbar-user-section">
        @auth
            <!-- Dashboard Links -->
            <div class="navbar-dashboard-links">
                @if(auth()->user()->department_id == 1)
                    <a href="{{ route('hr.dashboard') }}" class="btn {{ Request::routeIs('hr.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="clipboard-check" style="width: 16px; height: 16px;"></i>
                        <span>HR Dashboard</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'head')
                    <a href="{{ route('head.dashboard') }}" class="btn {{ Request::routeIs('head.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="users" style="width: 16px; height: 16px;"></i>
                        <span>Tim Saya</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['lead', 'co-lead']))
                    <a href="{{ route('lead.dashboard') }}" class="btn {{ Request::routeIs('lead.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
                        <span>Kepemimpinan</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'member')
                    <a href="{{ route('member.dashboard') }}" class="btn {{ Request::routeIs('member.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                        <span>Dashboard Saya</span>
                    </a>
                @endif
            </div>

            <!-- User Info -->
            <a href="{{ route('profile.show', auth()->user()) }}" class="navbar-user-info">
                <div class="navbar-avatar">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: capitalize;">
                        {{ str_replace('-', ' ', auth()->user()->role) }}
                    </div>
                </div>
            </a>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                    <span>Keluar</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">
                Member Login
            </a>
        @endauth
    </div>

    <!-- Mobile Hamburger -->
    <button class="navbar-toggle" onclick="toggleMobileMenu()">
        <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
    </button>
</nav>

<!-- Mobile Menu -->
<div id="mobileMenu" class="mobile-menu">
    @auth
        <!-- User Card -->
        <div class="mobile-user-card">
            <div class="mobile-user-avatar">
                @if(auth()->user()->avatar_path)
                    <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div style="font-weight: 700; font-size: 1.125rem; margin-bottom: 0.25rem;">{{ auth()->user()->name }}</div>
            <div style="opacity: 0.9; font-size: 0.875rem; text-transform: capitalize;">
                {{ str_replace('-', ' ', auth()->user()->role) }}
            </div>
        </div>

        <!-- Dashboard Links -->
        <div class="mobile-menu-section">
            <div class="mobile-menu-title">Dashboard</div>
            <div class="mobile-menu-links">
                @if(auth()->user()->department_id == 1)
                    <a href="{{ route('hr.dashboard') }}" class="mobile-menu-link">
                        <i data-lucide="clipboard-check" style="width: 20px; height: 20px;"></i>
                        <span>HR Dashboard</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'head')
                    <a href="{{ route('head.dashboard') }}" class="mobile-menu-link">
                        <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                        <span>Tim Saya</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['lead', 'co-lead']))
                    <a href="{{ route('lead.dashboard') }}" class="mobile-menu-link">
                        <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                        <span>Leadership Dashboard</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'member')
                    <a href="{{ route('member.dashboard') }}" class="mobile-menu-link">
                        <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                        <span>Dashboard Saya</span>
                    </a>
                @endif

                <a href="{{ route('profile.show', auth()->user()) }}" class="mobile-menu-link">
                    <i data-lucide="user-circle" style="width: 20px; height: 20px;"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>
    @endauth

    <!-- Navigation Links -->
    <div class="mobile-menu-section">
        <div class="mobile-menu-title">Navigasi</div>
        <div class="mobile-menu-links">
            <a href="{{ route('home') }}" class="mobile-menu-link">
                <i data-lucide="home" style="width: 20px; height: 20px;"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('public.leaderboard') }}" class="mobile-menu-link">
                <i data-lucide="trophy" style="width: 20px; height: 20px;"></i>
                <span>Papan Peringkat</span>
            </a>
            <a href="{{ route('posts.index') }}" class="mobile-menu-link">
                <i data-lucide="newspaper" style="width: 20px; height: 20px;"></i>
                <span>Postingan</span>
            </a>
            <a href="{{ route('public.badges') }}" class="mobile-menu-link">
                <i data-lucide="award" style="width: 20px; height: 20px;"></i>
                <span>Lencana</span>
            </a>
            <a href="{{ route('public.organization') }}" class="mobile-menu-link">
                <i data-lucide="users-2" style="width: 20px; height: 20px;"></i>
                <span>Tim Kami</span>
            </a>
        </div>
    </div>

    <!-- Auth Actions -->
    @auth
        <div class="mobile-menu-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-menu-link" style="width: 100%; border: none; cursor: pointer; background: rgba(234, 67, 53, 0.1); color: var(--google-red);">
                    <i data-lucide="log-out" style="width: 20px; height: 20px;"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    @else
        <div class="mobile-menu-section">
            <a href="{{ route('login') }}" class="mobile-menu-link" style="background: linear-gradient(135deg, var(--google-blue), var(--google-green)); color: white;">
                <i data-lucide="log-in" style="width: 20px; height: 20px;"></i>
                <span>Login Anggota</span>
            </a>
        </div>
    @endauth
</div>

<script>
    // Mobile Menu Toggle
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('active');

        // Update icon
        const icon = document.querySelector('.navbar-toggle i');
        if (menu.classList.contains('active')) {
            icon.setAttribute('data-lucide', 'x');
        } else {
            icon.setAttribute('data-lucide', 'menu');
        }
        lucide.createIcons();
    }

    // Close mobile menu when clicking a link
    document.querySelectorAll('.mobile-menu-link').forEach(link => {
        link.addEventListener('click', () => {
            const menu = document.getElementById('mobileMenu');
            menu.classList.remove('active');
            const icon = document.querySelector('.navbar-toggle i');
            icon.setAttribute('data-lucide', 'menu');
            lucide.createIcons();
        });
    });

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
