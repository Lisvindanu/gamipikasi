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
