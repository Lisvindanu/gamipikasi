@extends('layouts.public')

@section('title', $user->name . ' - Profil')

@push('styles')
<style>
    .profile-hero {
        background: linear-gradient(135deg, #4285F4 0%, #34A853 50%, #FBBC05 100%);
        padding: 4rem 0 8rem 0;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }

    .profile-main-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        margin-top: -6rem;
        padding: 3rem;
        margin-bottom: 2rem;
    }

    .profile-header-grid {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 2rem;
        align-items: center;
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid var(--border-color);
    }

    .profile-avatar-large {
        width: 150px;
        height: 150px;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: 700;
        box-shadow: 0 10px 30px rgba(66, 133, 244, 0.3);
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .profile-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        font-size: 1rem;
        color: var(--text-secondary);
        flex-wrap: wrap;
    }

    .profile-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--bg-light);
        border-radius: 12px;
    }

    .profile-rank-box {
        text-align: center;
        background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(52, 168, 83, 0.1));
        padding: 1.5rem 2rem;
        border-radius: 20px;
        min-width: 180px;
    }

    .profile-rank {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--google-blue);
    }

    .profile-rank-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
    }

    .profile-points {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stats-showcase {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-box {
        text-align: center;
        padding: 1.5rem;
        background: var(--bg-light);
        border-radius: 16px;
        transition: all 0.3s;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-value.positive { color: var(--google-green); }
    .stat-value.negative { color: var(--google-red); }
    .stat-value.blue { color: var(--google-blue); }
    .stat-value.yellow { color: #f9ab00; }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .achievements-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }

    .section-header-profile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .section-title-profile {
        font-size: 1.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .badge-showcase {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1.5rem;
    }

    .badge-display {
        background: white;
        border: 3px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem 1rem;
        text-align: center;
        transition: all 0.3s;
        position: relative;
    }

    .badge-display.earned {
        border-color: var(--google-yellow);
        background: linear-gradient(135deg, rgba(251, 188, 5, 0.05), rgba(251, 188, 5, 0.1));
        box-shadow: 0 4px 15px rgba(251, 188, 5, 0.2);
    }

    .badge-display.earned::before {
        content: '✓';
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 24px;
        height: 24px;
        background: var(--google-green);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-display.locked {
        opacity: 0.4;
        filter: grayscale(100%);
    }

    .badge-display:hover:not(.locked) {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .badge-emoji {
        font-size: 3rem;
        margin-bottom: 0.75rem;
    }

    .badge-name {
        font-weight: 700;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .badge-desc {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    .timeline-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--google-blue), var(--google-green));
        border-radius: 2px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.5rem;
        top: 0.5rem;
        width: 16px;
        height: 16px;
        background: white;
        border: 3px solid var(--google-blue);
        border-radius: 50%;
    }

    .timeline-item.positive::before {
        border-color: var(--google-green);
    }

    .timeline-item.negative::before {
        border-color: var(--google-red);
    }

    .timeline-content {
        background: var(--bg-light);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid var(--google-blue);
    }

    .timeline-item.positive .timeline-content {
        border-left-color: var(--google-green);
    }

    .timeline-item.negative .timeline-content {
        border-left-color: var(--google-red);
    }

    .timeline-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .timeline-category {
        font-weight: 700;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .timeline-points {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .timeline-points.positive { color: var(--google-green); }
    .timeline-points.negative { color: var(--google-red); }

    .timeline-note {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .timeline-meta {
        font-size: 0.875rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-secondary);
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .modal-close:hover {
        background: var(--bg-light);
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 24px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        overflow: hidden;
        position: relative;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-preview-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .profile-header-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .profile-avatar-large {
            margin: 0 auto;
        }

        .profile-meta {
            justify-content: center;
        }

        .profile-rank-box {
            margin: 0 auto;
        }

        .stats-showcase {
            grid-template-columns: repeat(2, 1fr);
        }

        .badge-showcase {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="profile-hero">
        <div class="profile-container">
            <div style="text-align: center; color: white;">
                <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">Member Profil</h1>
                <p style="font-size: 1.25rem; opacity: 0.9;">Lihat pencapaian dan kontribusi member GDGoC</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="profile-container">
        <!-- Profil Main Card -->
        <div class="profile-main-card">
            <div class="profile-header-grid">
                <div class="profile-avatar-large">
                    @if($user->avatar_path)
                        <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <div class="profile-meta">
                        <div class="profile-meta-item">
                            <i data-lucide="building" style="width: 18px; height: 18px;"></i>
                            {{ $user->department->name ?? 'No Department' }}
                        </div>
                        <div class="profile-meta-item">
                            <i data-lucide="user-check" style="width: 18px; height: 18px;"></i>
                            {{ ucfirst(str_replace('-', ' ', $user->role)) }}
                        </div>
                        <div class="profile-meta-item">
                            <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                            {{ $user->email }}
                        </div>
                    </div>
                </div>
                <div class="profile-rank-box">
                    <div class="profile-rank">#{{ $stats['rank'] }}</div>
                    <div class="profile-rank-label">Peringkat</div>
                    <div class="profile-points">{{ $stats['total_points'] }}</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Total Poin</div>
                </div>
            </div>

            @auth
                @if(auth()->id() === $user->id)
                    <!-- Edit Profil Button -->
                    <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--border-color);">
                        <button class="btn btn-primary" onclick="openEditModal()" style="flex: 1;">
                            <i data-lucide="edit" style="width: 18px; height: 18px;"></i>
                            Edit Profil
                        </button>
                        <button class="btn btn-secondary" onclick="openPasswordModal()" style="flex: 1;">
                            <i data-lucide="lock" style="width: 18px; height: 18px;"></i>
                            Ubah Password
                        </button>
                        <button class="btn btn-secondary" onclick="openAvatarModal()" style="flex: 1;">
                            <i data-lucide="camera" style="width: 18px; height: 18px;"></i>
                            Ubah Foto
                        </button>
                    </div>
                @endif
            @endauth

            <!-- Statistics Showcase -->
            <div class="stats-showcase">
                <div class="stat-box">
                    <div class="stat-icon">📈</div>
                    <div class="stat-value positive">+{{ $stats['positive_points'] }}</div>
                    <div class="stat-label">Poin Positif</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">📉</div>
                    <div class="stat-value negative">{{ $stats['negative_points'] }}</div>
                    <div class="stat-label">Poin Negatif</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">📊</div>
                    <div class="stat-value blue">{{ $stats['total_assessments'] }}</div>
                    <div class="stat-label">Total Penilaian</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-value yellow">{{ $stats['badges_count'] }}</div>
                    <div class="stat-label">Badge Terkumpul</div>
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="achievements-section">
            <div class="section-header-profile">
                <h3 class="section-title-profile">
                    <span>🏅</span>
                    Koleksi Badge
                </h3>
                <span style="color: var(--text-secondary); font-size: 1rem;">{{ $stats['badges_count'] }} / {{ $allBadges->count() }} Badge</span>
            </div>
            <div class="badge-showcase">
                @foreach($allBadges as $badge)
                    @php
                        $earned = in_array($badge->id, $earnedBadgeIds);
                    @endphp
                    <div class="badge-display {{ $earned ? 'earned' : 'locked' }}">
                        <div class="badge-emoji">{{ $badge->emoji }}</div>
                        <div class="badge-name">{{ $badge->name }}</div>
                        <div class="badge-desc">{{ $badge->description }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="timeline-section">
            <div class="section-header-profile">
                <h3 class="section-title-profile">
                    <span>📜</span>
                    Riwayat Aktivitas
                </h3>
            </div>
            <div class="timeline">
                @forelse($recentActivities as $activity)
                    <div class="timeline-item {{ $activity->value > 0 ? 'positive' : 'negative' }}">
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <div class="timeline-category">
                                    @if($activity->category === 'commitment')
                                        <span>💪</span>
                                    @elseif($activity->category === 'collaboration')
                                        <span>🤝</span>
                                    @elseif($activity->category === 'initiative')
                                        <span>💡</span>
                                    @elseif($activity->category === 'responsibility')
                                        <span>✅</span>
                                    @elseif($activity->category === 'violation')
                                        <span>⚠️</span>
                                    @endif
                                    {{ ucfirst($activity->category) }}
                                </div>
                                <div class="timeline-points {{ $activity->value > 0 ? 'positive' : 'negative' }}">
                                    {{ $activity->value > 0 ? '+' : '' }}{{ $activity->value }}
                                </div>
                            </div>
                            <div class="timeline-note">{{ $activity->note }}</div>
                            <div class="timeline-meta">
                                <span>
                                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                    Dinilai oleh {{ $activity->assessor->name ?? 'System' }}
                                </span>
                                <span>
                                    <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                        <div style="font-size: 1.125rem;">Belum ada riwayat aktivitas</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @auth
        @if(auth()->id() === $user->id)
            <!-- Edit Profil Modal -->
            <div class="modal" id="edit-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Profil</h3>
                        <button class="modal-close" onclick="closeEditModal()">×</button>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="input-group" style="margin-bottom: 1.5rem;">
                            <label class="label-modern">Nama Lengkap<span class="required">*</span></label>
                            <input type="text" name="name" class="input-modern" value="{{ $user->name }}" required style="padding-left: 1rem;">
                        </div>

                        <div class="input-group" style="margin-bottom: 2rem;">
                            <label class="label-modern">Email<span class="required">*</span></label>
                            <input type="email" name="email" class="input-modern" value="{{ $user->email }}" required style="padding-left: 1rem;">
                        </div>

                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem;">
                            <i data-lucide="check" style="width: 20px; height: 20px;"></i>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password Modal -->
            <div class="modal" id="password-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Ubah Password</h3>
                        <button class="modal-close" onclick="closePasswordModal()">×</button>
                    </div>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        <div class="input-group" style="margin-bottom: 1.5rem;">
                            <label class="label-modern">Password Saat Ini<span class="required">*</span></label>
                            <input type="password" name="current_password" class="input-modern" required style="padding-left: 1rem;">
                        </div>

                        <div class="input-group" style="margin-bottom: 1.5rem;">
                            <label class="label-modern">Password Baru<span class="required">*</span></label>
                            <input type="password" name="password" class="input-modern" required style="padding-left: 1rem;">
                            <small style="color: var(--text-secondary); margin-top: 0.5rem; display: block;">Minimal 8 karakter</small>
                        </div>

                        <div class="input-group" style="margin-bottom: 2rem;">
                            <label class="label-modern">Konfirmasi Password Baru<span class="required">*</span></label>
                            <input type="password" name="password_confirmation" class="input-modern" required style="padding-left: 1rem;">
                        </div>

                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem;">
                            <i data-lucide="lock" style="width: 20px; height: 20px;"></i>
                            Ubah Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Avatar Modal -->
            <div class="modal" id="avatar-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Ubah Foto Profil</h3>
                        <button class="modal-close" onclick="closeAvatarModal()">×</button>
                    </div>

                    <form action="{{ route('profile.update-avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="avatar-preview" id="avatar-preview">
                            @if($user->avatar_path)
                                <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="Avatar Preview" id="preview-img">
                            @else
                                <div class="avatar-preview-placeholder" id="preview-placeholder">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group" style="margin-bottom: 2rem;">
                            <label class="label-modern">Pilih Foto<span class="required">*</span></label>
                            <input type="file" name="avatar" class="input-modern" accept="image/*" required onchange="previewAvatar(event)" style="padding: 1rem;">
                            <small style="color: var(--text-secondary); margin-top: 0.5rem; display: block;">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        </div>

                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem;">
                            <i data-lucide="upload" style="width: 20px; height: 20px;"></i>
                            Upload Foto
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endauth

    <script>
        // Modal functions
        function openEditModal() {
            document.getElementById('edit-modal').classList.add('active');
            if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.remove('active');
        }

        function openPasswordModal() {
            document.getElementById('password-modal').classList.add('active');
            if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        }

        function closePasswordModal() {
            document.getElementById('password-modal').classList.remove('active');
        }

        function openAvatarModal() {
            document.getElementById('avatar-modal').classList.add('active');
            if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        }

        function closeAvatarModal() {
            document.getElementById('avatar-modal').classList.remove('active');
        }

        // Preview avatar
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('preview-placeholder');

                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }

                    let img = document.getElementById('preview-img');
                    if (!img) {
                        img = document.createElement('img');
                        img.id = 'preview-img';
                        preview.appendChild(img);
                    }
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }

        if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    </script>
@endsection
