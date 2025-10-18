@extends('layouts.app')

@section('title', 'Head of Department Dashboard')

@push('styles')
<style>
    .dept-header {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        border-radius: 20px;
        padding: 2.5rem 2rem;
        color: white;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .dept-icon-big {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        backdrop-filter: blur(10px);
    }

    .dept-info-header {
        flex: 1;
    }

    .dept-name-header {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .dept-desc-header {
        opacity: 0.9;
        font-size: 1rem;
    }

    .stats-grid-head {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card-head {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: var(--shadow-md);
        text-align: center;
    }

    .stat-card-head .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1rem;
    }

    .stat-card-head .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-card-head .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .anggota-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .member-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        border-radius: 12px;
        background: var(--bg-light);
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .member-card:hover {
        background: white;
        box-shadow: var(--shadow-sm);
    }

    .member-avatar-big {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--google-blue);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .member-info {
        flex: 1;
    }

    .member-name {
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 0.25rem;
    }

    .member-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .member-poin {
        text-align: right;
    }

    .member-poin-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--google-green);
        margin-bottom: 0.25rem;
    }

    .member-poin-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .alert-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-top: 2rem;
    }

    .alert-info {
        background: rgba(66, 133, 244, 0.1);
        border-left: 4px solid var(--google-blue);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .alert-info-title {
        font-weight: 700;
        color: var(--google-blue);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-info-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .request-alert-btn {
        width: 100%;
        padding: 1.25rem;
        background: linear-gradient(135deg, var(--google-yellow), var(--google-red));
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
        gap: 0.75rem;
    }

    .request-alert-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 1024px) {
        .stats-grid-head {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .dept-header {
            flex-direction: column;
            text-align: center;
        }

        .stats-grid-head {
            grid-template-columns: 1fr;
        }

        .dept-name-header {
            font-size: 1.5rem;
        }
    }

    /* Task Management Styles */
    .tasks-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-top: 2rem;
    }

    .task-card {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--google-blue);
        transition: all 0.3s;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .task-card.priority-high {
        border-left-color: var(--google-red);
    }

    .task-card.priority-medium {
        border-left-color: var(--google-yellow);
    }

    .task-card.priority-low {
        border-left-color: var(--google-green);
    }

    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .task-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .task-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .task-meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .task-actions {
        display: flex;
        gap: 0.5rem;
    }

    .priority-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-badge.high {
        background: rgba(234, 67, 53, 0.1);
        color: var(--google-red);
    }

    .priority-badge.medium {
        background: rgba(251, 188, 4, 0.1);
        color: #f57c00;
    }

    .priority-badge.low {
        background: rgba(52, 168, 83, 0.1);
        color: var(--google-green);
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.tertunda {
        background: rgba(66, 133, 244, 0.1);
        color: var(--google-blue);
    }

    .status-badge.in_progress {
        background: rgba(251, 188, 4, 0.1);
        color: #f57c00;
    }

    .status-badge.selesai {
        background: rgba(52, 168, 83, 0.1);
        color: var(--google-green);
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 0;
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
        color: var(--text-primary);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e8eaed;
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--google-blue);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn-task-create {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }

    .btn-task-create:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .overdue-indicator {
        color: var(--google-red);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>
@endpush

@section('content')
<!-- Department Header -->
<div class="dept-header">
    <div class="dept-icon-big">🏢</div>
    <div class="dept-info-header">
        <h1 class="dept-name-header">{{ $department->name }}</h1>
        <p class="dept-desc-header">{{ $department->description }}</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid-head">
    <div class="stat-card-head">
        <div class="stat-icon blue">👥</div>
        <div class="stat-value">{{ $stats['total_anggota'] ?? 0 }}</div>
        <div class="stat-label">Team Members</div>
    </div>

    <div class="stat-card-head">
        <div class="stat-icon green">⭐</div>
        <div class="stat-value">{{ $stats['total_poin'] ?? 0 }}</div>
        <div class="stat-label">Total Poin</div>
    </div>

    <div class="stat-card-head">
        <div class="stat-icon yellow">📊</div>
        <div class="stat-value">{{ round($stats['average_poin'] ?? 0, 1) }}</div>
        <div class="stat-label">Rata-rata Poin</div>
    </div>

    <div class="stat-card-head">
        <div class="stat-icon red">🏆</div>
        <div class="stat-value">{{ $stats['top_performer']->name ?? '-' }}</div>
        <div class="stat-label">Top Performer</div>
    </div>
</div>

<!-- Department Members -->
<div class="anggota-section">
    <div class="card-header">
        <h2 class="card-title">👥 Department Members</h2>
        <p class="card-subtitle">Monitor your team's performance</p>
    </div>

    <div style="margin-top: 1.5rem;">
        @forelse($members as $member)
            <div class="member-card">
                <div class="member-avatar-big">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div class="member-info">
                    <div class="member-name">{{ $member->name }}</div>
                    <div class="member-email">{{ $member->email }}</div>
                </div>
                <div class="member-poin">
                    <div class="member-poin-value">{{ $member->total_poin }}</div>
                    <div class="member-poin-label">poin</div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                <div>No anggota in this department yet</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Task Management Section -->
<div class="tasks-section">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="card-title">📋 Task Management</h2>
            <p class="card-subtitle">Assign and track tasks for your team</p>
        </div>
        <button class="btn-task-create" onclick="openTaskModal()">
            <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
            <span>Assign New Task</span>
        </button>
    </div>

    <div style="margin-top: 1.5rem;">
        @forelse($tasks as $task)
            <div class="task-card priority-{{ $task->priority }}">
                <div class="task-header">
                    <div style="flex: 1;">
                        <div class="task-title">{{ $task->title }}</div>
                        @if($task->description)
                            <div style="color: var(--text-secondary); font-size: 0.9375rem; margin-bottom: 0.75rem;">
                                {{ $task->description }}
                            </div>
                        @endif
                        <div class="task-meta">
                            <div class="task-meta-item">
                                <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                                <span>{{ $task->assignedTo->name }}</span>
                            </div>
                            @if($task->deadline)
                                <div class="task-meta-item {{ $task->isOverdue() ? 'overdue-indicator' : '' }}">
                                    <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                                    <span>{{ $task->deadline->format('d M Y') }}</span>
                                    @if($task->isOverdue())
                                        <span>(Overdue!)</span>
                                    @endif
                                </div>
                            @endif
                            @if($task->point_reward)
                                <div class="task-meta-item">
                                    <i data-lucide="award" style="width: 16px; height: 16px;"></i>
                                    <span>{{ $task->point_reward }} poin</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                        <span class="priority-badge {{ $task->priority }}">{{ $task->priority }}</span>
                        <span class="status-badge {{ $task->status }}">{{ str_replace('_', ' ', $task->status) }}</span>
                    </div>
                </div>

                @if($task->status === 'selesai')
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e8eaed;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                <i data-lucide="check-circle" style="width: 16px; height: 16px; display: inline;"></i>
                                Completed on {{ $task->selesai_at->format('d M Y H:i') }}
                                @if($task->completion_note)
                                    <div style="margin-top: 0.5rem;">Note: {{ $task->completion_note }}</div>
                                @endif
                            </div>
                            @if($task->point_reward)
                                <button class="btn btn-success btn-sm" disabled>
                                    <i data-lucide="star" style="width: 16px; height: 16px;"></i>
                                    Points Awarded
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="task-actions" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e8eaed;">
                        @if($task->status === 'tertunda')
                            <button class="btn btn-sm btn-secondary" onclick="updateTaskStatus({{ $task->id }}, 'in_progress')">
                                <i data-lucide="play" style="width: 16px; height: 16px;"></i>
                                Mark as In Progress
                            </button>
                        @endif
                        @if($task->status === 'in_progress')
                            <button class="btn btn-sm btn-success" onclick="updateTaskStatus({{ $task->id }}, 'selesai')">
                                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                                Mark as Completed
                            </button>
                        @endif
                        <button class="btn btn-sm btn-danger" onclick="deleteTask({{ $task->id }})">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                            Delete
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                <div>No tasks assigned yet</div>
                <div style="margin-top: 0.5rem; font-size: 0.875rem;">Click "Assign New Task" to create your first task</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Task Creation Modal -->
<div id="taskModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Assign New Task</h3>
            <button class="modal-close" onclick="closeTaskModal()">×</button>
        </div>
        <form id="taskForm" onsubmit="createTask(event)">
            <div class="form-group">
                <label class="form-label">Task Title *</label>
                <input type="text" name="title" class="form-input" required placeholder="e.g., Prepare event presentation">
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-textarea" placeholder="Add task details..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Assign To *</label>
                <select name="assigned_to" class="form-select" required>
                    <option value="">Select team member</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Prioritas *</label>
                <select name="priority" class="form-select" required>
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deadline</label>
                <input type="date" name="deadline" class="form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Point Reward (0-50)</label>
                <input type="number" name="point_reward" class="form-input" min="0" max="50" placeholder="e.g., 10">
                <small style="color: var(--text-secondary); font-size: 0.875rem;">Member will receive these poin upon completion</small>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i data-lucide="check" style="width: 20px; height: 20px;"></i>
                    Create Task
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeTaskModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Deadlines Section -->
<div class="deadline-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Deadline yang Akan Datang Widget -->
    <div class="deadline-card" style="background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-md);">
        <div class="card-header" style="margin-bottom: 1.5rem;">
            <h2 class="card-title" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">⏰ Deadline yang Akan Datang</h2>
            <p class="card-subtitle" style="color: var(--text-secondary); font-size: 0.875rem;">Tasks due within 7 days</p>
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
                                        <span>{{ $task->assignedTo->name }}</span>
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
                <div style="margin-top: 0.5rem; font-size: 0.875rem;">All tasks are on track!</div>
            </div>
        @endif
    </div>

    <!-- Tugas Terlambat & Stats Widget -->
    <div class="deadline-card" style="background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-md);">
        <div class="card-header" style="margin-bottom: 1.5rem;">
            <h2 class="card-title" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">🚨 Tugas Terlambat</h2>
            <p class="card-subtitle" style="color: var(--text-secondary); font-size: 0.875rem;">Tasks that need immediate attention</p>
        </div>

        @if($overdueTasks->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                @foreach($overdueTasks->take(3) as $task)
                    <div class="deadline-item" style="padding: 1.25rem; border-radius: 12px; background: #fef2f2; border-left: 4px solid var(--google-red);">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--google-red);">{{ $task->title }}</div>
                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                <span>{{ $task->assignedTo->name }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--google-red);">
                                <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                                <span>{{ $task->days_overdue }} {{ $task->days_overdue == 1 ? 'day' : 'days' }} overdue</span>
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

<!-- Alert/Feedback Section -->
<div class="alert-section">
    <div class="card-header">
        <h2 class="card-title">📢 Request HR Attention</h2>
        <p class="card-subtitle">Alert HR team about member performance</p>
    </div>

    <div style="margin-top: 1.5rem;">
        <div class="alert-info">
            <div class="alert-info-title">
                <i data-lucide="info" style="width: 20px; height: 20px;"></i>
                How it works
            </div>
            <div class="alert-info-text">
                As a Head of Department, you can request HR team to review specific anggota who need attention.
                This could be for outstanding performance that deserves recognition, or for anggota who need
                additional support. HR will review your request and take appropriate action.
            </div>
        </div>

        <button class="request-alert-btn" onclick="alert('Feature coming soon! HR will be notified via email.')">
            <i data-lucide="bell" style="width: 24px; height: 24px;"></i>
            <span>Request HR Review for Member</span>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    // Task Modal Functions
    function openTaskModal() {
        document.getElementById('taskModal').classList.add('active');
    }

    function closeTaskModal() {
        document.getElementById('taskModal').classList.remove('active');
        document.getElementById('taskForm').reset();
    }

    // Create Task
    async function createTask(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route("head.tasks.create") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert('Task created successfully!');
                closeTaskModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while creating the task');
        }
    }

    // Update Task Status
    async function updateTaskStatus(taskId, status) {
        if (!confirm('Are you sure you want to update this task status?')) {
            return;
        }

        try {
            const response = await fetch(`/head/tasks/${taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating the task');
        }
    }

    // Delete Task
    async function deleteTask(taskId) {
        if (!confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/head/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('Task deleted successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the task');
        }
    }

    // Close modal when clicking outside
    document.getElementById('taskModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeTaskModal();
        }
    });
</script>
@endpush
