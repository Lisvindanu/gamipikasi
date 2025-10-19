<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GDGoC Gamification System">
    <title>@yield('title', 'Dashboard') - GDGoC Gamification</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- GSAP Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="navbar-logo" style="background: linear-gradient(135deg, var(--google-blue), var(--google-green)); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;">
                    🎯
                </div>
                <h1 class="navbar-title">GDGoC Gamification</h1>
            </div>

            <!-- Main Navigation (Desktop) -->
            <nav class="navbar-links-desktop" style="display: flex; gap: 1.5rem;">
                <a href="{{ route('home') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: color 0.3s;" onmouseover="this.style.color='var(--google-blue)'" onmouseout="this.style.color='var(--text-primary)'">
                    Beranda
                </a>
                <a href="{{ route('public.leaderboard') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: color 0.3s;" onmouseover="this.style.color='var(--google-blue)'" onmouseout="this.style.color='var(--text-primary)'">
                    Papan Peringkat
                </a>
                <a href="{{ route('posts.index') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: color 0.3s;" onmouseover="this.style.color='var(--google-blue)'" onmouseout="this.style.color='var(--text-primary)'">
                    Postingan
                </a>
                <a href="{{ route('public.badges') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: color 0.3s;" onmouseover="this.style.color='var(--google-blue)'" onmouseout="this.style.color='var(--text-primary)'">
                    Lencana
                </a>
            </nav>
        </div>

        <!-- Desktop Menu -->
        <div class="navbar-menu navbar-menu-desktop">
            @auth
                <!-- Dashboard Links -->
                <div style="display: flex; gap: 0.5rem; margin-right: 1rem;">
                    {{-- HR Dashboard --}}
                    @if(auth()->user()->department_id == 1)
                        <a href="{{ route('hr.dashboard') }}" class="btn {{ Request::routeIs('hr.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="clipboard-check" style="width: 16px; height: 16px;"></i>
                            <span>HR Dashboard</span>
                        </a>
                    @endif

                    {{-- Head Dashboard --}}
                    @if(auth()->user()->role === 'head')
                        <a href="{{ route('head.dashboard') }}" class="btn {{ Request::routeIs('head.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="users" style="width: 16px; height: 16px;"></i>
                            <span>Tim Saya</span>
                        </a>
                        <a href="{{ route('head.tasks.board') }}" class="btn {{ Request::routeIs('head.tasks.board') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="kanban-square" style="width: 16px; height: 16px;"></i>
                            <span>Papan Tugas</span>
                        </a>
                    @endif

                    {{-- Lead/Co-Lead Dashboard --}}
                    @if(in_array(auth()->user()->role, ['lead', 'co-lead']))
                        <a href="{{ route('lead.dashboard') }}" class="btn {{ Request::routeIs('lead.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
                            <span>Kepemimpinan</span>
                        </a>
                        <a href="{{ route('lead.tasks.board') }}" class="btn {{ Request::routeIs('lead.tasks.board') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="kanban-square" style="width: 16px; height: 16px;"></i>
                            <span>Papan Tugas</span>
                        </a>
                        <a href="{{ route('lead.activity-log') }}" class="btn {{ Request::routeIs('lead.activity-log') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="activity" style="width: 16px; height: 16px;"></i>
                            <span>Log Aktivitas</span>
                        </a>
                        <a href="{{ route('lead.settings') }}" class="btn {{ Request::routeIs('lead.settings') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="settings" style="width: 16px; height: 16px;"></i>
                            <span>Pengaturan</span>
                        </a>
                    @endif

                    {{-- User Management (for Lead, Co-Lead, and HR Head Lisvindanu) --}}
                    @if(in_array(auth()->user()->role, ['lead', 'co-lead']) || (auth()->user()->role === 'head' && auth()->user()->email === 'Lisvindanu015@gmail.com'))
                        <a href="{{ route('lead.users.index') }}" class="btn {{ Request::routeIs('lead.users.index') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="users-cog" style="width: 16px; height: 16px;"></i>
                            <span>Kelola Pengguna</span>
                        </a>
                    @endif

                    {{-- Secretary & Bendahara Dashboard --}}
                    @if(in_array(auth()->user()->role, ['secretary', 'bendahara']))
                        <a href="{{ route('member.dashboard') }}" class="btn {{ Request::routeIs('member.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('member.tasks.board') }}" class="btn {{ Request::routeIs('member.tasks.board') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="kanban-square" style="width: 16px; height: 16px;"></i>
                            <span>Papan Tugas</span>
                        </a>
                    @endif

                    {{-- Member Dashboard --}}
                    @if(auth()->user()->role === 'member')
                        <a href="{{ route('member.dashboard') }}" class="btn {{ Request::routeIs('member.dashboard') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('member.tasks.board') }}" class="btn {{ Request::routeIs('member.tasks.board') ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem;">
                            <i data-lucide="kanban-square" style="width: 16px; height: 16px;"></i>
                            <span>Papan Tugas</span>
                        </a>
                    @endif
                </div>

                <!-- Notification Bell -->
                <div style="position: relative;">
                    <button id="notificationBell" onclick="toggleNotifications()" class="btn btn-secondary" style="padding: 0.5rem; position: relative;">
                        <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                        <span id="notificationBadge" class="notification-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: var(--google-red); color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.65rem; font-weight: 700; display: flex; align-items: center; justify-content: center; border: 2px solid white;">0</span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; width: 400px; max-height: 500px; overflow-y: auto; background: white; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); z-index: 1000;">
                        <div style="padding: 1rem 1.5rem; border-bottom: 2px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="font-weight: 700; font-size: 1rem;">Notifikasi</h3>
                            <button onclick="markAllAsRead()" class="btn btn-sm btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">
                                Tandai semua dibaca
                            </button>
                        </div>
                        <div id="notificationList" style="max-height: 400px; overflow-y: auto;">
                            <!-- Notifications will be loaded here -->
                            <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                                <i data-lucide="bell-off" style="width: 48px; height: 48px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                <p>Memuat notifikasi...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.show', auth()->user()) }}" class="navbar-user" style="text-decoration: none; color: inherit; transition: all 0.3s;" onmouseover="this.style.background='rgba(66,133,244,0.1)'" onmouseout="this.style.background='var(--bg-light)'">
                    <div class="navbar-avatar" style="overflow: hidden;">
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
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            @endauth
        </div>

        <!-- Mobile Hamburger Button -->
        <button class="navbar-toggle" onclick="toggleMobileMenu()" style="display: none;">
            <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
        </button>
    </nav>

    <!-- Mobile Menu -->
    @auth
    <div id="mobileMenuAuth" class="mobile-menu-auth" style="display: none;">
        <!-- User Card -->
        <div style="background: linear-gradient(135deg, var(--google-blue), var(--google-green)); color: white; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; text-align: center;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: white; color: var(--google-blue); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem; overflow: hidden;">
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
        <div style="margin-bottom: 1.5rem;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.75rem;">Dashboard</div>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
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
                    <a href="{{ route('head.tasks.board') }}" class="mobile-menu-link">
                        <i data-lucide="kanban-square" style="width: 20px; height: 20px;"></i>
                        <span>Papan Tugas</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['lead', 'co-lead']))
                    <a href="{{ route('lead.dashboard') }}" class="mobile-menu-link">
                        <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                        <span>Kepemimpinan</span>
                    </a>
                    <a href="{{ route('lead.tasks.board') }}" class="mobile-menu-link">
                        <i data-lucide="kanban-square" style="width: 20px; height: 20px;"></i>
                        <span>Papan Tugas</span>
                    </a>
                    <a href="{{ route('lead.activity-log') }}" class="mobile-menu-link">
                        <i data-lucide="activity" style="width: 20px; height: 20px;"></i>
                        <span>Log Aktivitas</span>
                    </a>
                    <a href="{{ route('lead.settings') }}" class="mobile-menu-link">
                        <i data-lucide="settings" style="width: 20px; height: 20px;"></i>
                        <span>Pengaturan</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['lead', 'co-lead']) || (auth()->user()->role === 'head' && auth()->user()->email === 'Lisvindanu015@gmail.com'))
                    <a href="{{ route('lead.users.index') }}" class="mobile-menu-link">
                        <i data-lucide="users-cog" style="width: 20px; height: 20px;"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['secretary', 'bendahara', 'member']))
                    <a href="{{ route('member.dashboard') }}" class="mobile-menu-link">
                        <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                        <span>Dashboard Saya</span>
                    </a>
                    <a href="{{ route('member.tasks.board') }}" class="mobile-menu-link">
                        <i data-lucide="kanban-square" style="width: 20px; height: 20px;"></i>
                        <span>Papan Tugas</span>
                    </a>
                @endif

                <a href="{{ route('profile.show', auth()->user()) }}" class="mobile-menu-link">
                    <i data-lucide="user-circle" style="width: 20px; height: 20px;"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>

        <!-- Navigation Links -->
        <div style="margin-bottom: 1.5rem;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.75rem;">Navigasi</div>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
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
            </div>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mobile-menu-link" style="width: 100%; border: none; cursor: pointer; background: rgba(234, 67, 53, 0.1); color: var(--google-red);">
                <i data-lucide="log-out" style="width: 20px; height: 20px;"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
    @endauth

    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-modern">
        <p>© 2025 Google Developer Groups on Campus - Universitas Pasundan</p>
    </footer>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h3 class="loading-title">Memproses...</h3>
            <p class="loading-text">Mohon tunggu</p>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Mobile Menu Toggle
        window.toggleMobileMenu = function() {
            const menu = document.getElementById('mobileMenuAuth');
            const toggle = document.querySelector('.navbar-toggle i');

            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
                toggle.setAttribute('data-lucide', 'x');
            } else {
                menu.style.display = 'none';
                toggle.setAttribute('data-lucide', 'menu');
            }
            lucide.createIcons();
        };

        // Close mobile menu when clicking a link
        document.querySelectorAll('.mobile-menu-link').forEach(link => {
            link.addEventListener('click', function() {
                const menu = document.getElementById('mobileMenuAuth');
                const toggle = document.querySelector('.navbar-toggle i');
                if (menu) {
                    menu.style.display = 'none';
                    if (toggle) {
                        toggle.setAttribute('data-lucide', 'menu');
                        lucide.createIcons();
                    }
                }
            });
        });

        // Helper function to show loading
        window.showLoading = function() {
            document.getElementById('loading-overlay').classList.add('active');
        };

        // Helper function to hide loading
        window.hideLoading = function() {
            document.getElementById('loading-overlay').classList.remove('active');
        };

        // Notification System
        @auth
        let notificationsLoaded = false;

        // Toggle notification dropdown
        window.toggleNotifications = function() {
            const dropdown = document.getElementById('notificationDropdown');
            const isVisible = dropdown.style.display !== 'none';

            if (isVisible) {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
                if (!notificationsLoaded) {
                    loadNotifications();
                    notificationsLoaded = true;
                }
            }
        };

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const bell = document.getElementById('notificationBell');
            const dropdown = document.getElementById('notificationDropdown');

            if (bell && dropdown && !bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Load notifications from server
        async function loadNotifications() {
            const listContainer = document.getElementById('notificationList');

            try {
                const response = await fetch('{{ route('notifications.index') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                // Update badge count
                updateBadgeCount(data.unread_count);

                // Render notifications
                renderNotifications(data.notifications);

            } catch (error) {
                console.error('Error loading notifications:', error);
                listContainer.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                        <i data-lucide="alert-circle" style="width: 48px; height: 48px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        <p>Failed to load notifications</p>
                    </div>
                `;
                lucide.createIcons();
            }
        }

        // Update notification badge count
        function updateBadgeCount(count) {
            const badge = document.getElementById('notificationBadge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Render notifications in dropdown
        function renderNotifications(notifications) {
            const listContainer = document.getElementById('notificationList');

            if (notifications.length === 0) {
                listContainer.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                        <i data-lucide="bell-off" style="width: 48px; height: 48px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        <p>No notifications yet</p>
                    </div>
                `;
                lucide.createIcons();
                return;
            }

            const notificationItems = notifications.map(notif => {
                const notifData = typeof notif.data === 'string' ? JSON.parse(notif.data) : notif.data;
                const taskId = notifData?.task_id || null;

                // Icon based on notification type
                let icon = 'bell';
                let iconColor = 'var(--google-blue)';

                if (notif.type === 'task_assigned') {
                    icon = 'clipboard-check';
                    iconColor = 'var(--google-blue)';
                } else if (notif.type === 'task_selesai') {
                    icon = 'check-circle';
                    iconColor = 'var(--google-green)';
                } else if (notif.type === 'comment_added') {
                    icon = 'message-circle';
                    iconColor = 'var(--google-yellow)';
                } else if (notif.type === 'badge_awarded') {
                    icon = 'award';
                    iconColor = 'var(--google-red)';
                }

                const bgColor = notif.read ? 'transparent' : 'rgba(66, 133, 244, 0.05)';
                const timeAgo = formatTimeAgo(notif.created_at);

                return `
                    <div onclick="handleNotificationClick(${notif.id}, ${taskId})"
                         style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); cursor: pointer; transition: all 0.3s; background: ${bgColor};"
                         onmouseover="this.style.background='rgba(66, 133, 244, 0.05)'"
                         onmouseout="this.style.background='${bgColor}'">
                        <div style="display: flex; gap: 1rem; align-items: start;">
                            <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, ${iconColor}, ${iconColor}22); display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="${icon}" style="width: 20px; height: 20px; color: ${iconColor};"></i>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: ${notif.read ? '500' : '700'}; font-size: 0.875rem; margin-bottom: 0.25rem; color: var(--text-primary);">
                                    ${notif.title}
                                </div>
                                <div style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                                    ${notif.message}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    ${timeAgo}
                                </div>
                            </div>
                            ${!notif.read ? '<div style="width: 8px; height: 8px; border-radius: 50%; background: var(--google-blue); flex-shrink: 0; margin-top: 0.5rem;"></div>' : ''}
                        </div>
                    </div>
                `;
            }).join('');

            listContainer.innerHTML = notificationItems;
            lucide.createIcons();
        }

        // Handle notification click
        window.handleNotificationClick = async function(notificationId, taskId) {
            try {
                // Mark as read
                await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                // Reload notifications to update UI
                await loadNotifications();

                // Navigate to task if taskId exists
                if (taskId) {
                    // Determine which dashboard based on user role
                    @if(auth()->user()->role === 'member')
                        window.location.href = '{{ route('member.tasks.board') }}';
                    @elseif(auth()->user()->role === 'head')
                        window.location.href = '{{ route('head.tasks.board') }}';
                    @elseif(in_array(auth()->user()->role, ['lead', 'co-lead']))
                        window.location.href = '{{ route('lead.tasks.board') }}';
                    @endif
                }

            } catch (error) {
                console.error('Error handling notification click:', error);
            }
        };

        // Mark all notifications as read
        window.markAllAsRead = async function() {
            try {
                const response = await fetch('{{ route('notifications.read-all') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Reload notifications to update UI
                    await loadNotifications();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        };

        // Format time ago (e.g., "2 minutes ago", "1 hour ago")
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'Just now';

            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;

            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;

            const days = Math.floor(hours / 24);
            if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`;

            const weeks = Math.floor(days / 7);
            if (weeks < 4) return `${weeks} week${weeks > 1 ? 's' : ''} ago`;

            const months = Math.floor(days / 30);
            if (months < 12) return `${months} month${months > 1 ? 's' : ''} ago`;

            const years = Math.floor(days / 365);
            return `${years} year${years > 1 ? 's' : ''} ago`;
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();

            // Auto-refresh notifications every 60 seconds
            setInterval(loadNotifications, 60000);
        });
        @endauth
    </script>

    @stack('scripts')
</body>
</html>
