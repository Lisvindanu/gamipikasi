@extends('layouts.app')

@section('title', 'Curriculum Developer Dashboard')

@push('styles')
<style>
    .curriculum-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        color: var(--text-secondary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        background: rgba(66, 133, 244, 0.1);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .departments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .dept-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .dept-header {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e8eaed;
    }

    .member-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .member-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .member-info {
        flex: 1;
        min-width: 0;
    }

    .member-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .member-points {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .curriculum-dashboard {
            padding: 1rem;
        }

        .departments-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="curriculum-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>👋 Halo, {{ $user->name }}!</h1>
        <p>Dashboard untuk Head of Curriculum Developer - Manage semua departemen kurikulum</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $stats['total_members'] }}</div>
            <div class="stat-label">Total Curriculum Members</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-value">{{ $stats['total_points'] }}</div>
            <div class="stat-label">Total Points</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-value">{{ $stats['avg_points'] }}</div>
            <div class="stat-label">Average Points</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-value">{{ $stats['total_tasks'] }}</div>
            <div class="stat-label">Active Tasks</div>
        </div>
    </div>

    <!-- Departments Grid -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h2 class="card-title">Curriculum Departments</h2>
            <p class="card-subtitle">Semua departemen yang Anda manage</p>
        </div>

        <div class="departments-grid">
            @forelse($membersByDept as $deptName => $deptMembers)
                <div class="dept-card">
                    <div class="dept-header">
                        {{ $deptName }}
                        <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-left: 0.5rem;">
                            ({{ $deptMembers->count() }} members)
                        </span>
                    </div>

                    <div class="member-list">
                        @forelse($deptMembers as $member)
                            <div class="member-item">
                                <div class="member-avatar">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div class="member-info">
                                    <div class="member-name">{{ $member->name }}</div>
                                    <div class="member-points">{{ $member->total_points }} points</div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <p style="font-size: 0.875rem;">Belum ada member</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <p>Belum ada departemen curriculum</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Task Overview -->
    @if($tasks->count() > 0)
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Tasks</h2>
            <p class="card-subtitle">Tasks dari semua curriculum departments</p>
        </div>

        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Department</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $task->title }}</div>
                                @if($task->description)
                                    <div style="font-size: 0.8125rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                        {{ Str::limit($task->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-modern">{{ $task->department->name ?? '-' }}</span>
                            </td>
                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                            <td>
                                @if($task->status === 'selesai')
                                    <span class="badge-status" style="background: rgba(52, 168, 83, 0.1); color: var(--google-green);">Selesai</span>
                                @elseif($task->status === 'sedang_dikerjakan')
                                    <span class="badge-status" style="background: rgba(251, 188, 5, 0.1); color: #f9ab00;">Sedang Dikerjakan</span>
                                @else
                                    <span class="badge-status" style="background: rgba(66, 133, 244, 0.1); color: var(--google-blue);">Backlog</span>
                                @endif
                            </td>
                            <td>
                                @if($task->deadline)
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
