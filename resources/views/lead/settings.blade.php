@extends('layouts.app')

@section('title', 'System Pengaturan')

@push('styles')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .setting-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        text-align: center;
    }

    .setting-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1rem;
    }

    .setting-icon.blue { background: rgba(66, 133, 244, 0.1); }
    .setting-icon.green { background: rgba(52, 168, 83, 0.1); }
    .setting-icon.yellow { background: rgba(251, 188, 5, 0.1); }
    .setting-icon.red { background: rgba(234, 67, 53, 0.1); }

    .setting-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .setting-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .action-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
    }

    .posts-list {
        display: grid;
        gap: 1rem;
        margin-top: 1rem;
    }

    .post-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.3s;
    }

    .post-item:hover {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.05);
    }

    .post-item.pinned {
        border-color: var(--google-yellow);
        background: rgba(251, 188, 5, 0.05);
    }

    .post-info {
        flex: 1;
    }

    .post-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .post-meta {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
</style>
@endpush

@section('content')
<div class="content-grid">
    <!-- Page Header -->
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">⚙️ Pengaturan Sistem</h1>
            <p class="card-subtitle">Kelola konfigurasi dan data sistem gamifikasi</p>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="settings-grid">
        <div class="setting-card">
            <div class="setting-icon blue">👥</div>
            <div class="setting-value">{{ $stats['total_members'] }}</div>
            <div class="setting-label">Total Members</div>
        </div>
        <div class="setting-card">
            <div class="setting-icon green">⭐</div>
            <div class="setting-value">{{ $stats['total_points_given'] }}</div>
            <div class="setting-label">Poin Diberikan</div>
        </div>
        <div class="setting-card">
            <div class="setting-icon yellow">📊</div>
            <div class="setting-value">{{ $stats['total_assessments'] }}</div>
            <div class="setting-label">Total Penilaian</div>
        </div>
        <div class="setting-card">
            <div class="setting-icon red">📝</div>
            <div class="setting-value">{{ $stats['total_posts'] }}</div>
            <div class="setting-label">Total Posts</div>
        </div>
        <div class="setting-card">
            <div class="setting-icon blue">📌</div>
            <div class="setting-value">{{ $stats['pinned_posts'] }}</div>
            <div class="setting-label">Pinned Posts</div>
        </div>
        <div class="setting-card">
            <div class="setting-icon green">🏢</div>
            <div class="setting-value">{{ $stats['departments'] }}</div>
            <div class="setting-label">Departments</div>
        </div>
    </div>

    <!-- Export Data Section -->
    <div class="action-card">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">📥 Export Data</h2>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Download data sistem dalam format CSV</p>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <form action="{{ route('lead.export.points') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                    Export Data Poin
                </button>
            </form>

            <form action="{{ route('lead.export.members') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i data-lucide="users" style="width: 16px; height: 16px;"></i>
                    Export Data Members
                </button>
            </form>
        </div>
    </div>

    <!-- Pinned Posts Management -->
    <div class="action-card">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">📌 Pinned Posts</h2>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            Manage posts yang di-pin di halaman utama. Edit post untuk pin/unpin.
        </p>

        <div class="posts-list">
            @forelse($pinnedPosts as $post)
                <div class="post-item pinned">
                    <div style="font-size: 1.5rem;">📌</div>
                    <div class="post-info">
                        <div class="post-title">{{ $post->title }}</div>
                        <div class="post-meta">
                            oleh {{ $post->author->name }} • {{ $post->created_at->diffForHumans() }}
                            @if($post->department)
                                • {{ $post->department->name }}
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                        Edit
                    </a>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📌</div>
                    <div>Tidak ada post yang di-pin saat ini</div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Postingan Terbaru -->
    <div class="action-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;">📝 Postingan Terbaru</h2>
                <p style="color: var(--text-secondary);">
                    @if($recentPosts->count() > 0)
                        {{ $recentPosts->count() }} post terbaru di sistem
                    @else
                        Belum ada post di sistem
                    @endif
                </p>
            </div>
            @if($recentPosts->count() > 0)
                <a href="{{ route('posts.index') }}" class="btn btn-primary">
                    <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                    Lihat Semua
                </a>
            @else
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                    Buat Post Pertama
                </a>
            @endif
        </div>

        <div class="posts-list">
            @forelse($recentPosts as $post)
                <div class="post-item {{ $post->is_pinned ? 'pinned' : '' }}">
                    @if($post->is_pinned)
                        <div style="font-size: 1.5rem;">📌</div>
                    @else
                        <div style="font-size: 1.5rem;">📄</div>
                    @endif
                    <div class="post-info">
                        <div class="post-title">{{ $post->title }}</div>
                        <div class="post-meta">
                            oleh {{ $post->author->name }} • {{ $post->created_at->diffForHumans() }}
                            @if($post->department)
                                • {{ $post->department->name }}
                            @endif
                            • <span style="text-transform: capitalize;">{{ $post->category }}</span>
                        </div>
                    </div>
                    <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                        Lihat
                    </a>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
                    <div>Belum ada post yang dibuat</div>
                    <div style="margin-top: 1rem;">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                            Buat Post Pertama
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="action-card">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">⚡ Quick Actions</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <a href="{{ route('lead.dashboard') }}" class="btn btn-secondary" style="justify-content: center;">
                <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                Dashboard
            </a>
            <a href="{{ route('posts.create') }}" class="btn btn-secondary" style="justify-content: center;">
                <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                Buat Post
            </a>
            <a href="{{ route('public.leaderboard') }}" class="btn btn-secondary" style="justify-content: center;">
                <i data-lucide="trophy" style="width: 20px; height: 20px;"></i>
                Leaderboard
            </a>
            <a href="{{ route('hr.dashboard') }}" class="btn btn-secondary" style="justify-content: center;">
                <i data-lucide="users-cog" style="width: 20px; height: 20px;"></i>
                HR Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
</script>
@endpush
