@extends('layouts.app')

@section('title', 'Activity Log')

@push('styles')
<style>
    .activity-header {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--google-blue);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .filters-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .timeline-container {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .timeline {
        position: relative;
        padding-left: 3rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--google-blue), var(--google-green));
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 1rem;
    }

    .timeline-marker {
        position: absolute;
        left: -2.35rem;
        top: 0.5rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1;
    }

    .timeline-card {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .timeline-card:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.75rem;
    }

    .timeline-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .timeline-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--google-blue);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .timeline-user-info {
        display: flex;
        flex-direction: column;
    }

    .timeline-username {
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .timeline-userrole {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: capitalize;
    }

    .timeline-time {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .timeline-description {
        font-size: 0.9375rem;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .timeline-metadata {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .timeline-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        background: white;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .timeline-tag i {
        width: 14px;
        height: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .timeline {
            padding-left: 2rem;
        }

        .timeline-marker {
            left: -1.85rem;
            width: 32px;
            height: 32px;
        }
    }
</style>
@endpush

@section('content')
<div class="activity-header">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">=Ý Activity Log</h1>
    <p style="opacity: 0.9;">Track all task-related activities across the organization</p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_activities'] ?? 0 }}</div>
        <div class="stat-label">Total Activities</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['today_activities'] ?? 0 }}</div>
        <div class="stat-label">Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['this_week_activities'] ?? 0 }}</div>
        <div class="stat-label">This Week</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $stats['this_month_activities'] ?? 0 }}</div>
        <div class="stat-label">This Month</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-card">
    <form method="GET" action="{{ route('lead.activity-log') }}">
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">User</label>
                <select name="user_id" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Action Type</label>
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="task_created" {{ request('action') == 'task_created' ? 'selected' : '' }}>Task Created</option>
                    <option value="task_status_changed" {{ request('action') == 'task_status_changed' ? 'selected' : '' }}>Status Changed</option>
                    <option value="task_completed" {{ request('action') == 'task_completed' ? 'selected' : '' }}>Task Completed</option>
                    <option value="comment_added" {{ request('action') == 'comment_added' ? 'selected' : '' }}>Comment Added</option>
                    <option value="attachment_uploaded" {{ request('action') == 'attachment_uploaded' ? 'selected' : '' }}>Attachment Uploaded</option>
                    <option value="attachment_deleted" {{ request('action') == 'attachment_deleted' ? 'selected' : '' }}>Attachment Deleted</option>
                    <option value="task_deleted" {{ request('action') == 'task_deleted' ? 'selected' : '' }}>Task Deleted</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>

            <div class="filter-group">
                <label class="filter-label">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>

            <div class="filter-group">
                <label class="filter-label">&nbsp;</label>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                        Filter
                    </button>
                    <a href="{{ route('lead.activity-log') }}" class="btn btn-secondary">
                        <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Timeline -->
<div class="timeline-container">
    <h2 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="activity" style="width: 24px; height: 24px; color: var(--google-blue);"></i>
        Activity Timeline
    </h2>

    @if($activities->count() > 0)
        <div class="timeline">
            @foreach($activities as $activity)
                <div class="timeline-item">
                    <div class="timeline-marker" style="background: {{ $activity->color }}; color: white;">
                        <i data-lucide="{{ $activity->icon }}" style="width: 20px; height: 20px;"></i>
                    </div>

                    <div class="timeline-card" style="border-left-color: {{ $activity->color }};">
                        <div class="timeline-header">
                            <div class="timeline-user">
                                <div class="timeline-avatar">
                                    {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="timeline-user-info">
                                    <div class="timeline-username">{{ $activity->user->name ?? 'Unknown User' }}</div>
                                    <div class="timeline-userrole">{{ str_replace('-', ' ', $activity->user->role ?? 'user') }}</div>
                                </div>
                            </div>
                            <div class="timeline-time">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>

                        <div class="timeline-description">
                            {{ $activity->description }}
                        </div>

                        @if($activity->metadata)
                            <div class="timeline-metadata">
                                @if(isset($activity->metadata['task_title']))
                                    <span class="timeline-tag">
                                        <i data-lucide="file-text"></i>
                                        {{ $activity->metadata['task_title'] }}
                                    </span>
                                @endif

                                @if(isset($activity->metadata['priority']))
                                    <span class="timeline-tag" style="background: {{ $activity->metadata['priority'] === 'high' ? 'rgba(234, 67, 53, 0.1)' : ($activity->metadata['priority'] === 'medium' ? 'rgba(251, 188, 4, 0.1)' : 'rgba(52, 168, 83, 0.1)') }}; color: {{ $activity->metadata['priority'] === 'high' ? 'var(--google-red)' : ($activity->metadata['priority'] === 'medium' ? '#f57c00' : 'var(--google-green)') }};">
                                        <i data-lucide="flag"></i>
                                        {{ ucfirst($activity->metadata['priority']) }}
                                    </span>
                                @endif

                                @if(isset($activity->metadata['old_status']) && isset($activity->metadata['new_status']))
                                    <span class="timeline-tag">
                                        <i data-lucide="arrow-right"></i>
                                        {{ ucfirst(str_replace('_', ' ', $activity->metadata['old_status'])) }} ’ {{ ucfirst(str_replace('_', ' ', $activity->metadata['new_status'])) }}
                                    </span>
                                @endif

                                @if(isset($activity->metadata['point_reward']))
                                    <span class="timeline-tag" style="background: rgba(251, 188, 4, 0.1); color: #f57c00;">
                                        <i data-lucide="award"></i>
                                        +{{ $activity->metadata['point_reward'] }} points
                                    </span>
                                @endif

                                @if(isset($activity->metadata['file_name']))
                                    <span class="timeline-tag">
                                        <i data-lucide="paperclip"></i>
                                        {{ $activity->metadata['file_name'] }}
                                    </span>
                                @endif

                                @if(isset($activity->metadata['deadline']))
                                    <span class="timeline-tag">
                                        <i data-lucide="calendar"></i>
                                        Due: {{ \Carbon\Carbon::parse($activity->metadata['deadline'])->format('d M Y') }}
                                    </span>
                                @endif

                                @if($activity->task)
                                    <a href="{{ route('lead.tasks.board') }}" class="timeline-tag" style="background: var(--google-blue); color: white; text-decoration: none;">
                                        <i data-lucide="external-link"></i>
                                        View Task
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">=í</div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">No Activities Found</h3>
            <p>No activity logs match your current filters. Try adjusting the filters or reset to see all activities.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }

        // Animate timeline items on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateX(-20px)';

                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.5s ease-out';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateX(0)';
                    }, 100);

                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.timeline-item').forEach(item => {
            observer.observe(item);
        });
    });
</script>
@endpush
