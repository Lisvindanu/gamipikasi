@extends('layouts.app')

@section('title', 'Dashboard Saya')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        border-radius: 20px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        text-align: center;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
        color: var(--google-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-role {
        font-size: 1rem;
        opacity: 0.9;
        text-transform: capitalize;
    }

    .points-showcase {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-top: 2rem;
    }

    .points-showcase-item {
        text-align: center;
    }

    .points-showcase-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .points-showcase-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .progress-section {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-top: 2rem;
    }

    .progress-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        text-align: center;
    }

    .progress-emoji {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
    }

    .progress-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .progress-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .progress-value.positive { color: var(--google-green); }
    .progress-value.negative { color: var(--google-red); }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: var(--bg-light);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s;
    }

    .progress-bar.commitment { background: #4285f4; }
    .progress-bar.collaboration { background: #34a853; }
    .progress-bar.initiative { background: #fbbc05; }
    .progress-bar.responsibility { background: #ea4335; }
    .progress-bar.violation { background: #999; }

    .badges-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-top: 2rem;
    }

    .badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .badge-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 1.5rem 1rem;
        text-align: center;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .badge-card.earned {
        background: white;
        border-color: var(--google-yellow);
        box-shadow: 0 4px 12px rgba(251, 188, 5, 0.2);
    }

    .badge-card.earned:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(251, 188, 5, 0.3);
    }

    .badge-icon {
        font-size: 3rem;
        margin-bottom: 0.75rem;
        filter: grayscale(100%);
        opacity: 0.4;
    }

    .badge-card.earned .badge-icon {
        filter: none;
        opacity: 1;
    }

    .badge-name {
        font-weight: 700;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .badge-desc {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .badge-date {
        font-size: 0.7rem;
        color: var(--google-blue);
        margin-top: 0.5rem;
        font-weight: 600;
    }

    .history-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-top: 2rem;
    }

    .history-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        border-radius: 12px;
        background: var(--bg-light);
        margin-bottom: 1rem;
    }

    .history-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .history-icon.positive { background: rgba(52, 168, 83, 0.1); }
    .history-icon.negative { background: rgba(234, 67, 53, 0.1); }

    .history-content {
        flex: 1;
    }

    .history-category {
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: capitalize;
        margin-bottom: 0.25rem;
    }

    .history-note {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .history-meta {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .history-points {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: right;
    }

    .history-points.positive { color: var(--google-green); }
    .history-points.negative { color: var(--google-red); }

    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 24px;
        border: 2px solid var(--border-color);
        background: white;
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-btn:hover {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.05);
    }

    .filter-btn.active {
        border-color: var(--google-blue);
        background: var(--google-blue);
        color: white;
    }

    .history-item {
        transition: all 0.3s;
    }

    .history-item.hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .points-showcase {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .progress-section {
            grid-template-columns: 1fr;
        }

        .profile-name {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Profile Header -->
<div class="profile-header">
    <div class="profile-avatar">
        @if($user->avatar_path)
            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        @else
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        @endif
    </div>
    <h1 class="profile-name">{{ $user->name ?? 'Member' }}</h1>
    <p class="profile-role">{{ str_replace('-', ' ', $user->role ?? 'member') }} - {{ $user->department->name ?? 'Tanpa Departemen' }}</p>

    <div class="points-showcase">
        <div class="points-showcase-item">
            <div class="points-showcase-value">{{ $dashboard['total_points'] ?? 0 }}</div>
            <div class="points-showcase-label">Total Poin</div>
        </div>
        <div class="points-showcase-item">
            <div class="points-showcase-value">#{{ $dashboard['rank'] ?? '-' }}</div>
            <div class="points-showcase-label">Peringkat</div>
        </div>
        <div class="points-showcase-item">
            <div class="points-showcase-value">{{ count($dashboard['badges'] ?? []) }}</div>
            <div class="points-showcase-label">Lencana Diraih</div>
        </div>
    </div>
</div>

<!-- Point Breakdown -->
<div class="progress-section">
    @php
        $breakdown = $dashboard['point_breakdown'] ?? [];
        $categories = [
            'commitment' => ['emoji' => '💪', 'max' => 10],
            'collaboration' => ['emoji' => '🤝', 'max' => 10],
            'initiative' => ['emoji' => '💡', 'max' => 15],
            'responsibility' => ['emoji' => '✅', 'max' => 10],
            'violation' => ['emoji' => '⚠️', 'max' => 10],
        ];
    @endphp

    @foreach($categories as $key => $cat)
        @php
            $value = $breakdown[$key] ?? 0;
            $percentage = $key === 'violation' ? 0 : min(($value / $cat['max']) * 100, 100);
        @endphp
        <div class="progress-card">
            <div class="progress-emoji">{{ $cat['emoji'] }}</div>
            <div class="progress-label">{{ ucfirst($key) }}</div>
            <div class="progress-value {{ $value >= 0 ? 'positive' : 'negative' }}">
                {{ $value >= 0 ? '+' : '' }}{{ $value }}
            </div>
            @if($key !== 'violation')
                <div class="progress-bar-container">
                    <div class="progress-bar {{ $key }}" style="width: {{ $percentage }}%"></div>
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Badges -->
<div class="badges-section">
    <div class="card-header">
        <h2 class="card-title">🏅 My Badges</h2>
        <p class="card-subtitle">Collect badges by achieving milestones</p>
    </div>

    <div class="badges-grid">
        @foreach($allBadges ?? [] as $badge)
            @php
                $earned = collect($dashboard['badges'] ?? [])->firstWhere('id', $badge->id);
            @endphp
            <div class="badge-card {{ $earned ? 'earned' : '' }}">
                <div class="badge-icon">{{ $badge->icon }}</div>
                <div class="badge-name">{{ $badge->name }}</div>
                <div class="badge-desc">{{ $badge->description }}</div>
                @if($earned)
                    <div class="badge-date">
                        Earned {{ \Carbon\Carbon::parse($earned->pivot->earned_at)->format('d M Y') }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Recent History -->
<div class="history-section">
    <div class="card-header" style="margin-bottom: 1.5rem;">
        <h2 class="card-title">📊 Activity Timeline</h2>
        <p class="card-subtitle">Track your assessment history</p>
    </div>

    <!-- Activity Filter -->
    <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <button class="filter-btn active" data-filter="all" onclick="filterActivities('all')">
            Semua Aktivitas
        </button>
        <button class="filter-btn" data-filter="commitment" onclick="filterActivities('commitment')">
            💪 Commitment
        </button>
        <button class="filter-btn" data-filter="collaboration" onclick="filterActivities('collaboration')">
            🤝 Collaboration
        </button>
        <button class="filter-btn" data-filter="initiative" onclick="filterActivities('initiative')">
            💡 Initiative
        </button>
        <button class="filter-btn" data-filter="responsibility" onclick="filterActivities('responsibility')">
            ✅ Responsibility
        </button>
        <button class="filter-btn" data-filter="violation" onclick="filterActivities('violation')">
            ⚠️ Violation
        </button>
    </div>

    <div id="activity-timeline" style="margin-top: 1.5rem;">
        @forelse($dashboard['recent_points'] ?? [] as $point)
            <div class="history-item" data-category="{{ $point->category }}">
                <div class="history-icon {{ $point->value >= 0 ? 'positive' : 'negative' }}">
                    @if($point->category === 'commitment')
                        💪
                    @elseif($point->category === 'collaboration')
                        🤝
                    @elseif($point->category === 'initiative')
                        💡
                    @elseif($point->category === 'responsibility')
                        ✅
                    @elseif($point->category === 'violation')
                        ⚠️
                    @endif
                </div>
                <div class="history-content">
                    <div class="history-category">{{ ucfirst($point->category) }}</div>
                    <div class="history-note">{{ $point->note }}</div>
                    <div class="history-meta">
                        Dinilai oleh {{ $point->assessor->name ?? 'System' }} • {{ \Carbon\Carbon::parse($point->created_at)->diffForHumans() }}
                    </div>
                </div>
                <div class="history-points {{ $point->value >= 0 ? 'positive' : 'negative' }}">
                    {{ $point->value >= 0 ? '+' : '' }}{{ $point->value }}
                </div>
            </div>
        @empty
            <div id="empty-state" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                <div>Belum ada aktivitas penilaian</div>
            </div>
        @endforelse
    </div>

    @if(count($dashboard['recent_points'] ?? []) >= 10)
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('profile.show', $user) }}" class="btn btn-secondary">
                <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                Lihat Semua Aktivitas
            </a>
        </div>
    @endif
</div>

<!-- Deadlines Section -->
<div class="deadline-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Upcoming Deadlines Widget -->
    <div class="deadline-card" style="background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-md);">
        <div class="card-header" style="margin-bottom: 1.5rem;">
            <h2 class="card-title" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">⏰ Deadline Saya yang Akan Datang</h2>
            <p class="card-subtitle" style="color: var(--text-secondary); font-size: 0.875rem;">Tugas jatuh tempo dalam 7 hari</p>
        </div>

        @if($upcomingDeadlines->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($upcomingDeadlines->take(5) as $task)
                    <div class="deadline-item" style="padding: 1.25rem; border-radius: 12px; background: var(--bg-light); border-left: 4px solid {{ $task->urgency_color }}; transition: all 0.3s;">
                        <div style="display: flex; gap: 1rem; align-items: start;">
                            <!-- Urgency Badge -->
                            <div class="deadline-urgency" style="min-width: 60px; text-align: center; padding: 0.5rem; border-radius: 8px; background: {{ $task->urgency_color }}; color: white;">
                                <div class="deadline-urgency-icon" style="font-size: 1.5rem; margin-bottom: 0.25rem;">
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
                                <div style="font-size: 0.75rem; font-weight: 700;">
                                    @if($task->hours_remaining < 24)
                                        {{ floor($task->hours_remaining) }}h
                                    @else
                                        {{ ceil($task->days_remaining) }}d
                                    @endif
                                </div>
                            </div>

                            <!-- Task Info -->
                            <div style="flex: 1;">
                                <div style="font-weight: 600; margin-bottom: 0.5rem;">{{ $task->title }}</div>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                        <span>Dari {{ $task->assignedBy->name }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                        <span>{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div style="margin-top: 0.5rem;">
                                    <span class="priority-badge priority-{{ $task->priority }}" style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                        {{ $task->priority }}
                                    </span>
                                    @if($task->point_reward)
                                        <span style="display: inline-block; margin-left: 0.5rem; padding: 0.25rem 0.75rem; border-radius: 12px; background: var(--google-green); color: white; font-size: 0.75rem; font-weight: 600;">
                                            +{{ $task->point_reward }} pts
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                <div style="font-weight: 600;">No upcoming deadlines</div>
                <div style="margin-top: 0.5rem; font-size: 0.875rem;">Semua tugas Anda sesuai jadwal!</div>
            </div>
        @endif
    </div>

    <!-- Overdue Tasks & Stats Widget -->
    <div class="deadline-card" style="background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-md);">
        <div class="card-header" style="margin-bottom: 1.5rem;">
            <h2 class="card-title" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">🚨 Tugas Terlambat Saya</h2>
            <p class="card-subtitle" style="color: var(--text-secondary); font-size: 0.875rem;">Tugas yang memerlukan perhatian segera</p>
        </div>

        @if($overdueTasks->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                @foreach($overdueTasks->take(3) as $task)
                    <div class="deadline-item" style="padding: 1.25rem; border-radius: 12px; background: #fef2f2; border-left: 4px solid var(--google-red);">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--google-red);">{{ $task->title }}</div>
                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                <span>Dari {{ $task->assignedBy->name }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--google-red);">
                                <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                                <span>{{ $task->days_overdue }} {{ $task->days_overdue == 1 ? 'hari' : 'hari' }} terlambat</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary); margin-bottom: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
                <div style="font-weight: 600;">No overdue tasks</div>
                <div style="margin-top: 0.5rem; font-size: 0.875rem;">Great job keeping everything on time!</div>
            </div>
        @endif

        <!-- Deadline Statistics Grid -->
        <div class="deadline-stats-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--bg-light);">
            <div class="deadline-stat" style="text-align: center; padding: 1rem; border-radius: 12px; background: var(--bg-light);">
                <div class="deadline-stat-value" style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--google-red);">
                    {{ $deadlineStats['overdue'] ?? 0 }}
                </div>
                <div class="deadline-stat-label" style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Overdue</div>
            </div>
            <div class="deadline-stat" style="text-align: center; padding: 1rem; border-radius: 12px; background: var(--bg-light);">
                <div class="deadline-stat-value" style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--google-blue);">
                    {{ $deadlineStats['due_today'] ?? 0 }}
                </div>
                <div class="deadline-stat-label" style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">Due Today</div>
            </div>
            <div class="deadline-stat" style="text-align: center; padding: 1rem; border-radius: 12px; background: var(--bg-light);">
                <div class="deadline-stat-value" style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--google-yellow);">
                    {{ $deadlineStats['due_this_week'] ?? 0 }}
                </div>
                <div class="deadline-stat-label" style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">This Week</div>
            </div>
            <div class="deadline-stat" style="text-align: center; padding: 1rem; border-radius: 12px; background: var(--bg-light);">
                <div class="deadline-stat-value" style="font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--google-green);">
                    {{ $deadlineStats['due_this_month'] ?? 0 }}
                </div>
                <div class="deadline-stat-label" style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-transform: uppercase;">This Month</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter activities by category
    function filterActivities(category) {
        const items = document.querySelectorAll('.history-item');
        const buttons = document.querySelectorAll('.filter-btn');
        const emptyState = document.getElementById('empty-state');

        // Update active button
        buttons.forEach(btn => {
            if (btn.dataset.filter === category) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Filter items
        let visibleCount = 0;
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });

        // Show/hide empty state
        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
                emptyState.querySelector('div:last-child').textContent =
                    category === 'all'
                    ? 'Belum ada aktivitas penilaian'
                    : `Belum ada aktivitas ${category}`;
            } else {
                emptyState.style.display = 'none';
            }
        }
    }

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

        lucide.createIcons();
    });
</script>
@endpush
