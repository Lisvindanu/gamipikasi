@extends('layouts.app')

@section('title', 'Leadership Dashboard')

@push('styles')
<style>
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card-lead {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    .stat-card-lead::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--google-blue), var(--google-green), var(--google-yellow), var(--google-red));
    }

    .stat-card-lead .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1.25rem;
    }

    .stat-card-lead .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-card-lead .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .stat-card-lead .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .stat-change.up {
        background: rgba(52, 168, 83, 0.1);
        color: var(--google-green);
    }

    .stat-change.down {
        background: rgba(234, 67, 53, 0.1);
        color: var(--google-red);
    }

    .chart-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .dept-performance-grid {
        display: grid;
        gap: 1rem;
    }

    .dept-performance-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s;
    }

    .dept-performance-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateX(4px);
    }

    .dept-rank {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .dept-rank.first { background: linear-gradient(135deg, #FFD700, #FFA500); color: white; }
    .dept-rank.second { background: linear-gradient(135deg, #C0C0C0, #808080); color: white; }
    .dept-rank.third { background: linear-gradient(135deg, #CD7F32, #8B4513); color: white; }
    .dept-rank.other { background: var(--bg-light); color: var(--text-secondary); }

    .dept-info {
        flex: 1;
    }

    .dept-name-lead {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .dept-stats {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .dept-points {
        text-align: right;
    }

    .dept-points-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--google-blue);
    }

    .dept-points-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .leaderboard-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .leaderboard-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.25rem;
        border-radius: 12px;
        background: var(--bg-light);
        margin-bottom: 0.75rem;
        transition: all 0.3s;
    }

    .leaderboard-item:hover {
        background: white;
        box-shadow: var(--shadow-sm);
    }

    .leaderboard-rank {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .leaderboard-rank.gold { background: linear-gradient(135deg, #FFD700, #FFA500); color: white; }
    .leaderboard-rank.silver { background: linear-gradient(135deg, #C0C0C0, #808080); color: white; }
    .leaderboard-rank.bronze { background: linear-gradient(135deg, #CD7F32, #8B4513); color: white; }
    .leaderboard-rank.regular { background: white; color: var(--text-primary); border: 2px solid var(--border-color); }

    .leaderboard-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--google-blue);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .leaderboard-info {
        flex: 1;
    }

    .leaderboard-name {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .leaderboard-meta {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .leaderboard-points {
        text-align: right;
    }

    .leaderboard-points-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--google-green);
    }

    .leaderboard-points-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    @media (max-width: 1024px) {
        .stats-overview {
            grid-template-columns: repeat(2, 1fr);
        }

        .chart-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-overview {
            grid-template-columns: 1fr;
        }
    }

    /* Deadline Widget Styles */
    .deadline-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .deadline-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
    }

    .deadline-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        background: var(--bg-light);
        margin-bottom: 0.75rem;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .deadline-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .deadline-urgency {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        color: white;
        flex-shrink: 0;
    }

    .deadline-urgency-icon {
        font-size: 1.25rem;
        margin-bottom: 0.125rem;
    }

    .deadline-info {
        flex: 1;
        min-width: 0;
    }

    .deadline-title {
        font-weight: 600;
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .deadline-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .deadline-meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .deadline-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .deadline-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .deadline-stat {
        text-align: center;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 12px;
    }

    .deadline-stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .deadline-stat-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    @media (max-width: 1024px) {
        .deadline-section {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats Overview -->
<div class="stats-overview">
    <div class="stat-card-lead">
        <div class="stat-icon blue">👥</div>
        <div class="stat-value">{{ $stats['total_members'] ?? 0 }}</div>
        <div class="stat-label">Total Anggota</div>
        <div class="stat-change up">
            <i data-lucide="trending-up" style="width: 12px; height: 12px;"></i>
            +5 bulan ini
        </div>
    </div>

    <div class="stat-card-lead">
        <div class="stat-icon green">⭐</div>
        <div class="stat-value">{{ number_format($stats['total_points'] ?? 0) }}</div>
        <div class="stat-label">Total Poin</div>
        <div class="stat-change up">
            <i data-lucide="trending-up" style="width: 12px; height: 12px;"></i>
            +12% pertumbuhan
        </div>
    </div>

    <div class="stat-card-lead">
        <div class="stat-icon yellow">📊</div>
        <div class="stat-value">{{ round($stats['avg_points'] ?? 0, 1) }}</div>
        <div class="stat-label">Rata-rata Poin</div>
        <div class="stat-change up">
            <i data-lucide="trending-up" style="width: 12px; height: 12px;"></i>
            Di atas target
        </div>
    </div>

    <div class="stat-card-lead">
        <div class="stat-icon red">🏆</div>
        <div class="stat-value">{{ $stats['active_badges'] ?? 0 }}</div>
        <div class="stat-label">Badges Awarded</div>
        <div class="stat-change up">
            <i data-lucide="trending-up" style="width: 12px; height: 12px;"></i>
            Growing
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="chart-section">
    <!-- Department Performance -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🏢 Performa Departemen</h2>
            <p class="card-subtitle">Peringkat berdasarkan rata-rata poin</p>
        </div>

        <div class="dept-performance-grid">
            @foreach($departmentPerformance ?? [] as $index => $dept)
                @php
                    $rankClass = $index === 0 ? 'first' : ($index === 1 ? 'second' : ($index === 2 ? 'third' : 'other'));
                @endphp
                <div class="dept-performance-card">
                    <div class="dept-rank {{ $rankClass }}">
                        @if($index < 3)
                            {{ ['🥇', '🥈', '🥉'][$index] }}
                        @else
                            #{{ $index + 1 }}
                        @endif
                    </div>
                    <div class="dept-info">
                        <div class="dept-name-lead">{{ $dept['department_name'] }}</div>
                        <div class="dept-stats">{{ $dept['total_members'] }} anggota</div>
                    </div>
                    <div class="dept-points">
                        <div class="dept-points-value">{{ round($dept['average_points'], 1) }}</div>
                        <div class="dept-points-label">rata-rata poin</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">📈 Insight Cepat</h2>
            <p class="card-subtitle">Bulan ini</p>
        </div>

        <div style="margin-top: 1.5rem;">
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem; font-weight: 600;">Commitment</span>
                    <span style="font-size: 0.875rem; font-weight: 700; color: var(--google-blue);">85%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar commitment" style="width: 85%"></div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem; font-weight: 600;">Collaboration</span>
                    <span style="font-size: 0.875rem; font-weight: 700; color: var(--google-green);">78%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar collaboration" style="width: 78%"></div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem; font-weight: 600;">Initiative</span>
                    <span style="font-size: 0.875rem; font-weight: 700; color: var(--google-yellow);">92%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar initiative" style="width: 92%"></div>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem; font-weight: 600;">Responsibility</span>
                    <span style="font-size: 0.875rem; font-weight: 700; color: var(--google-red);">88%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar responsibility" style="width: 88%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deadlines Section -->
<div class="deadline-section">
    <!-- Upcoming Deadlines -->
    <div class="deadline-card">
        <div class="card-header" style="margin-bottom: 1.5rem;">
            <h2 class="card-title">⏰ Deadline yang Akan Datang</h2>
            <p class="card-subtitle">Tugas jatuh tempo dalam 7 hari</p>
        </div>

        @if($upcomingDeadlines->count() > 0)
            <div style="max-height: 400px; overflow-y: auto;">
                @foreach($upcomingDeadlines->take(5) as $task)
                    <div class="deadline-item" style="border-left-color: {{ $task->urgency_color }};">
                        <div class="deadline-urgency" style="background: {{ $task->urgency_color }};">
                            <div class="deadline-urgency-icon">
                                @if($task->urgency == 5)
                                    🔥
                                @elseif($task->urgency == 4)
                                    ⚠️
                                @elseif($task->urgency == 3)
                                    ⏰
                                @else
                                    📅
                                @endif
                            </div>
                            <div>
                                @if($task->hours_remaining < 24)
                                    {{ floor($task->hours_remaining) }}h
                                @else
                                    {{ ceil($task->days_remaining) }}d
                                @endif
                            </div>
                        </div>

                        <div class="deadline-info">
                            <div class="deadline-title">{{ $task->title }}</div>
                            <div class="deadline-meta">
                                <div class="deadline-meta-item">
                                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                    {{ $task->assignedTo->name }}
                                </div>
                                <div class="deadline-meta-item">
                                    <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                    {{ $task->deadline->format('d M') }}
                                </div>
                                <span class="deadline-badge" style="background: {{ $task->priority === 'high' ? 'rgba(234, 67, 53, 0.1)' : ($task->priority === 'medium' ? 'rgba(251, 188, 4, 0.1)' : 'rgba(52, 168, 83, 0.1)') }}; color: {{ $task->priority === 'high' ? 'var(--google-red)' : ($task->priority === 'medium' ? '#f57c00' : 'var(--google-green)') }};">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($upcomingDeadlines->count() > 5)
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="{{ route('lead.tasks.board') }}" class="btn btn-secondary btn-sm">
                        Lihat Semua ({{ $upcomingDeadlines->count() }})
                    </a>
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">✅</div>
                <div style="font-weight: 600; margin-bottom: 0.25rem;">Semua sudah selesai!</div>
                <div style="font-size: 0.875rem;">Tidak ada deadline yang akan datang</div>
            </div>
        @endif
    </div>

    <!-- Overdue & Stats -->
    <div class="deadline-card">
        <div class="card-header" style="margin-bottom: 1.5rem;">
            <h2 class="card-title">🚨 Tugas Terlambat</h2>
            <p class="card-subtitle">Memerlukan perhatian segera</p>
        </div>

        @if($overdueTasks->count() > 0)
            <div style="max-height: 240px; overflow-y: auto; margin-bottom: 1.5rem;">
                @foreach($overdueTasks->take(3) as $task)
                    <div class="deadline-item" style="border-left-color: var(--google-red); background: rgba(234, 67, 53, 0.05);">
                        <div class="deadline-urgency" style="background: var(--google-red);">
                            <div class="deadline-urgency-icon">⚡</div>
                            <div>{{ $task->days_overdue }}d</div>
                        </div>

                        <div class="deadline-info">
                            <div class="deadline-title">{{ $task->title }}</div>
                            <div class="deadline-meta">
                                <div class="deadline-meta-item">
                                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                    {{ $task->assignedTo->name }}
                                </div>
                                <div class="deadline-meta-item" style="color: var(--google-red); font-weight: 600;">
                                    <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                                    {{ $task->days_overdue }} hari terlambat
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem 1rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎉</div>
                <div style="font-size: 0.875rem;">Tidak ada tugas terlambat!</div>
            </div>
        @endif

        <!-- Deadline Stats -->
        <div class="deadline-stats-grid">
            <div class="deadline-stat">
                <div class="deadline-stat-value" style="color: var(--google-red);">{{ $deadlineStats['overdue'] ?? 0 }}</div>
                <div class="deadline-stat-label">Terlambat</div>
            </div>
            <div class="deadline-stat">
                <div class="deadline-stat-value" style="color: var(--google-yellow);">{{ $deadlineStats['due_today'] ?? 0 }}</div>
                <div class="deadline-stat-label">Jatuh Tempo Hari Ini</div>
            </div>
            <div class="deadline-stat">
                <div class="deadline-stat-value" style="color: var(--google-green);">{{ $deadlineStats['due_this_week'] ?? 0 }}</div>
                <div class="deadline-stat-label">Minggu Ini</div>
            </div>
            <div class="deadline-stat">
                <div class="deadline-stat-value" style="color: var(--google-blue);">{{ $deadlineStats['due_this_month'] ?? 0 }}</div>
                <div class="deadline-stat-label">Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

<!-- Leaderboards -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2rem;">
    <!-- Head Leaderboard -->
    <div class="leaderboard-section">
        <div class="card-header">
            <h2 class="card-title">🏅 Peringkat Head</h2>
            <p class="card-subtitle">Kepala departemen terbaik</p>
        </div>

        <div style="margin-top: 1.5rem;">
            @forelse($headLeaderboard ?? [] as $index => $member)
                @php
                    $rankClass = $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'regular'));
                @endphp
                <div class="leaderboard-item">
                    <div class="leaderboard-rank {{ $rankClass }}">
                        #{{ $index + 1 }}
                    </div>
                    <div class="leaderboard-avatar" style="@if($member->avatar_path) background-image: url('{{ asset('storage/' . $member->avatar_path) }}'); background-size: cover; background-position: center; @endif">
                        @if(!$member->avatar_path)
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="leaderboard-info">
                        <div class="leaderboard-name">{{ $member->name }}</div>
                        <div class="leaderboard-meta">
                            {{ $member->department->name ?? 'Tanpa Departemen' }}
                        </div>
                    </div>
                    <div class="leaderboard-points">
                        <div class="leaderboard-points-value">{{ $member->total_points }}</div>
                        <div class="leaderboard-points-label">poin</div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                    Belum ada data head
                </div>
            @endforelse
        </div>
    </div>

    <!-- Member Leaderboard -->
    <div class="leaderboard-section">
        <div class="card-header">
            <h2 class="card-title">⭐ Peringkat Staff & Member</h2>
            <p class="card-subtitle">Staff dan member terbaik</p>
        </div>

        <div style="margin-top: 1.5rem;">
            @forelse($memberLeaderboard ?? [] as $index => $member)
                @php
                    $rankClass = $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'regular'));
                @endphp
                <div class="leaderboard-item">
                    <div class="leaderboard-rank {{ $rankClass }}">
                        #{{ $index + 1 }}
                    </div>
                    <div class="leaderboard-avatar" style="@if($member->avatar_path) background-image: url('{{ asset('storage/' . $member->avatar_path) }}'); background-size: cover; background-position: center; @endif">
                        @if(!$member->avatar_path)
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="leaderboard-info">
                        <div class="leaderboard-name">{{ $member->name }}</div>
                        <div class="leaderboard-meta">
                            {{ $member->department->name ?? 'Tanpa Departemen' }}
                        </div>
                    </div>
                    <div class="leaderboard-points">
                        <div class="leaderboard-points-value">{{ $member->total_points }}</div>
                        <div class="leaderboard-points-label">poin</div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                    Belum ada data member
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animate progress bars
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });

        if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    });
</script>
@endpush
