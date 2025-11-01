@extends('layouts.app')

@section('title', 'Papan Tugas - Trello Style')

@push('styles')
<style>
    .trello-board {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .trello-column {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 1rem;
        min-height: 500px;
    }

    .trello-column-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.75rem 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
    }

    .trello-column-title {
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .trello-column-count {
        background: var(--google-blue);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .trello-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s;
        cursor: pointer;
        border-left: 4px solid var(--google-blue);
    }

    .trello-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .trello-card.priority-high {
        border-left-color: var(--google-red);
    }

    .trello-card.priority-medium {
        border-left-color: var(--google-yellow);
    }

    .trello-card.priority-low {
        border-left-color: var(--google-green);
    }

    .card-title {
        font-weight: 600;
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .card-assignee {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .card-meta {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.75rem;
    }

    .card-meta-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .card-attachments {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }

    .attachment-preview {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid var(--border-color);
    }

    .fab-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        border: none;
        box-shadow: var(--shadow-lg);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s;
        z-index: 50;
    }

    .fab-button:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 24px rgba(66, 133, 244, 0.4);
    }

    /* Modal Styles */
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
        max-height: 85vh;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 768px) {
        .modal-content {
            max-height: 90vh;
            padding: 1.5rem;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--bg-light);
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: var(--text-secondary);
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .modal-close:hover {
        background: var(--bg-light);
        color: var(--text-primary);
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e8eaed;
        border-radius: 12px;
        font-size: 0.9375rem;
        color: var(--text-primary);
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--google-blue);
        box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-select {
        background: white;
        cursor: pointer;
    }

    @media (max-width: 1024px) {
        .trello-board {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
            📋 Papan Tugas
        </h1>
        <p style="color: var(--text-secondary);">Trello-style task management for your team</p>
    </div>
    <a href="{{ route('lead.dashboard') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
        <span>Back to Dashboard</span>
    </a>
</div>

<!-- Trello Board -->
<div class="trello-board">
    <!-- Pending Column -->
    <div class="trello-column">
        <div class="trello-column-header">
            <div class="trello-column-title">
                <span>⏳ Pending</span>
                <div class="trello-column-count">{{ $tasksByStatus['pending']->count() }}</div>
            </div>
        </div>

        @forelse($tasksByStatus['pending'] as $task)
            <div class="trello-card priority-{{ $task->priority }}" onclick="viewTask({{ $task->id }})">
                <div class="card-title">{{ $task->title }}</div>
                <div class="card-assignee">
                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                    <span>{{ $task->assignedTo->name }}</span>
                </div>
                @if($task->description)
                    <div style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        {{ Str::limit($task->description, 100) }}
                    </div>
                @endif
                <div class="card-meta">
                    <div class="card-meta-item">
                        <i data-lucide="flag" style="width: 12px; height: 12px;"></i>
                        <span style="text-transform: capitalize;">{{ $task->priority }}</span>
                    </div>
                    @if($task->deadline)
                        <div class="card-meta-item {{ $task->isOverdue() ? 'overdue-indicator' : '' }}">
                            <i data-lucide="calendar" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->deadline->format('M d') }}</span>
                        </div>
                    @endif
                    @if($task->attachments->count() > 0)
                        <div class="card-meta-item">
                            <i data-lucide="paperclip" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->attachments->count() }}</span>
                        </div>
                    @endif
                    @if($task->comments->count() > 0)
                        <div class="card-meta-item">
                            <i data-lucide="message-circle" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->comments->count() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📭</div>
                <div style="font-size: 0.875rem;">Tidak ada tugas tertunda</div>
            </div>
        @endforelse
    </div>

    <!-- In Progress Column -->
    <div class="trello-column">
        <div class="trello-column-header">
            <div class="trello-column-title">
                <span>🔄 In Progress</span>
                <div class="trello-column-count" style="background: var(--google-yellow);">{{ $tasksByStatus['in_progress']->count() }}</div>
            </div>
        </div>

        @forelse($tasksByStatus['in_progress'] as $task)
            <div class="trello-card priority-{{ $task->priority }}" onclick="viewTask({{ $task->id }})">
                <div class="card-title">{{ $task->title }}</div>
                <div class="card-assignee">
                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                    <span>{{ $task->assignedTo->name }}</span>
                </div>
                @if($task->description)
                    <div style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        {{ Str::limit($task->description, 100) }}
                    </div>
                @endif
                @if($task->attachments->count() > 0)
                    <div class="card-attachments">
                        @foreach($task->attachments->take(3) as $attachment)
                            @if($attachment->isImage())
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Attachment" class="attachment-preview">
                            @endif
                        @endforeach
                    </div>
                @endif
                <div class="card-meta">
                    <div class="card-meta-item">
                        <i data-lucide="flag" style="width: 12px; height: 12px;"></i>
                        <span style="text-transform: capitalize;">{{ $task->priority }}</span>
                    </div>
                    @if($task->deadline)
                        <div class="card-meta-item {{ $task->isOverdue() ? 'overdue-indicator' : '' }}">
                            <i data-lucide="calendar" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->deadline->format('M d') }}</span>
                        </div>
                    @endif
                    @if($task->attachments->count() > 0)
                        <div class="card-meta-item">
                            <i data-lucide="paperclip" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->attachments->count() }}</span>
                        </div>
                    @endif
                    @if($task->comments->count() > 0)
                        <div class="card-meta-item">
                            <i data-lucide="message-circle" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->comments->count() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚙️</div>
                <div style="font-size: 0.875rem;">Tidak ada tugas sedang berjalan</div>
            </div>
        @endforelse
    </div>

    <!-- Completed Column -->
    <div class="trello-column">
        <div class="trello-column-header">
            <div class="trello-column-title">
                <span>✅ Completed</span>
                <div class="trello-column-count" style="background: var(--google-green);">{{ $tasksByStatus['completed']->count() }}</div>
            </div>
        </div>

        @forelse($tasksByStatus['completed'] as $task)
            <div class="trello-card priority-{{ $task->priority }}" onclick="viewTask({{ $task->id }})" style="opacity: 0.8;">
                <div class="card-title">{{ $task->title }}</div>
                <div class="card-assignee">
                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                    <span>{{ $task->assignedTo->name }}</span>
                </div>
                @if($task->description)
                    <div style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        {{ Str::limit($task->description, 100) }}
                    </div>
                @endif
                @if($task->attachments->count() > 0)
                    <div class="card-attachments">
                        @foreach($task->attachments->take(3) as $attachment)
                            @if($attachment->isImage())
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Attachment" class="attachment-preview">
                            @endif
                        @endforeach
                    </div>
                @endif
                <div class="card-meta">
                    @if($task->completed_at)
                        <div class="card-meta-item" style="color: var(--google-green);">
                            <i data-lucide="check-circle" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->completed_at->format('M d') }}</span>
                        </div>
                    @endif
                    @if($task->attachments->count() > 0)
                        <div class="card-meta-item">
                            <i data-lucide="paperclip" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->attachments->count() }}</span>
                        </div>
                    @endif
                    @if($task->comments->count() > 0)
                        <div class="card-meta-item">
                            <i data-lucide="message-circle" style="width: 12px; height: 12px;"></i>
                            <span>{{ $task->comments->count() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎯</div>
                <div style="font-size: 0.875rem;">Belum ada tugas selesai</div>
            </div>
        @endforelse
    </div>
</div>

<!-- FAB Button -->
<button class="fab-button" onclick="openCreateTaskModal()">
    <i data-lucide="plus" style="width: 28px; height: 28px;"></i>
</button>

<!-- Create Task Modal (sama seperti di head dashboard) -->
<div id="taskModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Buat Tugas Baru</h3>
            <button class="modal-close" onclick="closeTaskModal()">×</button>
        </div>
        <form id="taskForm" onsubmit="createTask(event)">
            <div class="form-group">
                <label class="form-label">Task Title *</label>
                <input type="text" name="title" class="form-input" required placeholder="cth., Siapkan laporan bulanan">
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-textarea" placeholder="Tambahkan detail tugas..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Assign To *</label>
                <select name="assigned_to" class="form-select" required>
                    <option value="">Pilih anggota</option>
                    @foreach($assignableUsers as $assignableUser)
                        <option value="{{ $assignableUser->id }}">
                            {{ $assignableUser->name }} ({{ ucfirst($assignableUser->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Prioritas *</label>
                <select name="priority" id="taskPriority" class="form-select" required>
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tenggat Waktu</label>
                <input type="date" name="deadline" class="form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Reward Poin (Opsional)</label>
                <input type="number" name="point_reward" id="taskPointReward" class="form-input" min="0" max="50" placeholder="Auto: 25 poin">
                <small style="color: var(--text-secondary); font-size: 0.875rem;">
                    <strong>Otomatis:</strong> Low = 10pt, Medium = 25pt, High = 40pt. Kosongkan untuk gunakan nilai otomatis.
                </small>
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

<!-- Task Detail Modal -->
<div id="taskDetailModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 class="modal-title">Task Details</h3>
            <button class="modal-close" onclick="closeTaskDetailModal()">×</button>
        </div>
        <div id="taskDetailContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Safe Lucide initialization (uses global function from layout)
    if (typeof window.initLucideIcons === 'function') {
        window.initLucideIcons();
    }

    function openCreateTaskModal() {
        document.getElementById('taskModal').classList.add('active');
    }

    function closeTaskModal() {
        document.getElementById('taskModal').classList.remove('active');
        document.getElementById('taskForm').reset();
        // Reset placeholder to medium
        updatePointPlaceholder('medium');
    }

    // Auto-update point reward placeholder based on priority
    function updatePointPlaceholder(priority) {
        const pointRewardInput = document.getElementById('taskPointReward');
        const pointMap = {
            'low': 10,
            'medium': 25,
            'high': 40
        };

        if (pointRewardInput) {
            pointRewardInput.placeholder = `Auto: ${pointMap[priority]} poin`;
        }
    }

    // Listen to priority changes
    document.addEventListener('DOMContentLoaded', function() {
        const prioritySelect = document.getElementById('taskPriority');
        if (prioritySelect) {
            prioritySelect.addEventListener('change', function() {
                updatePointPlaceholder(this.value);
            });
        }
    });

    async function createTask(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route("lead.tasks.create") }}', {
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

    async function viewTask(taskId) {
        // Find task data from the page
        const tasks = @json($tasks);
        const task = tasks.find(t => t.id === taskId);

        if (!task) {
            alert('Task not found');
            return;
        }

        // Build task detail HTML
        let html = `
            <div style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                            ${task.title}
                        </h2>
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; ${task.priority === 'high' ? 'background: rgba(234, 67, 53, 0.1); color: var(--google-red);' : (task.priority === 'medium' ? 'background: rgba(251, 188, 4, 0.1); color: #f57c00;' : 'background: rgba(52, 168, 83, 0.1); color: var(--google-green);')}">
                                ${task.priority}
                            </span>
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; ${task.status === 'completed' ? 'background: rgba(52, 168, 83, 0.1); color: var(--google-green);' : (task.status === 'in_progress' ? 'background: rgba(251, 188, 4, 0.1); color: #f57c00;' : 'background: rgba(66, 133, 244, 0.1); color: var(--google-blue);')}">
                                ${task.status.replace('_', ' ')}
                            </span>
                        </div>
                    </div>
                </div>

                ${task.description ? `<div style="color: var(--text-secondary); margin-bottom: 1rem; padding: 1rem; background: var(--bg-light); border-radius: 12px;">${task.description}</div>` : ''}

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="padding: 1rem; background: var(--bg-light); border-radius: 12px;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Assigned To</div>
                        <div style="font-weight: 600;">${task.assigned_to.name}</div>
                        <div style="font-size: 0.8125rem; color: var(--text-secondary);">${task.assigned_to.role}</div>
                    </div>
                    ${task.deadline ? `
                    <div style="padding: 1rem; background: var(--bg-light); border-radius: 12px;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Tenggat Waktu</div>
                        <div style="font-weight: 600;">${new Date(task.deadline).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                    </div>` : ''}
                    ${task.point_reward ? `
                    <div style="padding: 1rem; background: var(--bg-light); border-radius: 12px;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Reward Poin</div>
                        <div style="font-weight: 600; color: var(--google-blue);">${task.point_reward} points</div>
                    </div>` : ''}
                </div>

                <!-- Attachments -->
                ${task.attachments && task.attachments.length > 0 ? `
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="paperclip" style="width: 20px; height: 20px;"></i>
                        Attachments (${task.attachments.length})
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                        ${task.attachments.map(att => {
                            const isImage = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'].includes(att.file_type);
                            return `
                                <div style="border-radius: 8px; overflow: hidden; border: 2px solid var(--border-color);">
                                    ${isImage ?
                                        `<img src="/storage/${att.file_path}" style="width: 100%; height: 120px; object-fit: cover;">` :
                                        `<div style="width: 100%; height: 120px; background: var(--google-blue); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem;">📄</div>`
                                    }
                                    <div style="padding: 0.5rem; background: white;">
                                        <div style="font-size: 0.75rem; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${att.file_name}</div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>` : ''}

                <!-- Comments -->
                ${task.comments && task.comments.length > 0 ? `
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="message-circle" style="width: 20px; height: 20px;"></i>
                        Comments (${task.comments.length})
                    </h4>
                    ${task.comments.map(comment => `
                        <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--bg-light); border-radius: 8px; margin-bottom: 0.75rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--google-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                                ${comment.user.name.charAt(0).toUpperCase()}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem;">${comment.user.name}</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary);">${comment.comment}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>` : ''}

                <!-- Actions -->
                <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--bg-light);">
                    ${task.status !== 'completed' ? `
                        <button class="btn btn-success" onclick="updateTaskStatus(${task.id}, 'completed')">
                            <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                            Mark as Completed
                        </button>
                    ` : ''}
                    ${task.status !== 'cancelled' ? `
                        <button class="btn btn-danger" onclick="deleteTask(${task.id})">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                            Delete Task
                        </button>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('taskDetailContent').innerHTML = html;
        document.getElementById('taskDetailModal').classList.add('active');

        // Re-initialize lucide icons
        setTimeout(() => {
            if (typeof window.initLucideIcons === 'function') {
                window.initLucideIcons();
            }
        }, 100);
    }

    function closeTaskDetailModal() {
        document.getElementById('taskDetailModal').classList.remove('active');
    }

    async function updateTaskStatus(taskId, status) {
        if (!confirm('Are you sure you want to update this task status?')) {
            return;
        }

        try {
            const response = await fetch(`/lead/tasks/${taskId}/status`, {
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
                alert('Task status updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating the task');
        }
    }

    async function deleteTask(taskId) {
        if (!confirm('Are you sure you want to delete this task? This cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/lead/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                alert('Task deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the task');
        }
    }

    // Close modals when clicking outside
    document.getElementById('taskModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeTaskModal();
        }
    });

    document.getElementById('taskDetailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeTaskDetailModal();
        }
    });
</script>
@endpush
