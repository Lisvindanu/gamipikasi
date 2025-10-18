@extends('layouts.app')

@section('title', 'HR Dashboard')

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--google-blue), var(--google-green));
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
    }

    .stat-icon.blue { background: rgba(66, 133, 244, 0.1); }
    .stat-icon.green { background: rgba(52, 168, 83, 0.1); }
    .stat-icon.yellow { background: rgba(251, 188, 5, 0.1); }
    .stat-icon.red { background: rgba(234, 67, 53, 0.1); }

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

    .member-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .member-table th {
        background: var(--bg-light);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
        border-bottom: 2px solid var(--border-color);
    }

    .member-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.875rem;
    }

    .member-table tbody tr:hover {
        background: var(--bg-light);
    }

    .badge-role {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-role.lead { background: rgba(234, 67, 53, 0.1); color: var(--google-red); }
    .badge-role.co-lead { background: rgba(251, 188, 5, 0.1); color: #f9ab00; }
    .badge-role.head { background: rgba(66, 133, 244, 0.1); color: var(--google-blue); }
    .badge-role.member { background: rgba(52, 168, 83, 0.1); color: var(--google-green); }

    .points-display {
        font-weight: 700;
        font-size: 1rem;
    }

    .points-display.positive { color: var(--google-green); }
    .points-display.negative { color: var(--google-red); }

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

    .category-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .category-card {
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .category-card:hover {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.05);
    }

    .category-card.active {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.1);
    }

    .category-emoji {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .category-name {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .category-range {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .point-input-group {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        margin: 1.5rem 0;
    }

    .point-input-wrapper {
        flex: 1;
    }

    .point-input {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        font-family: "Google Sans", sans-serif;
    }

    .point-input:focus {
        outline: none;
        border-color: var(--google-blue);
    }

    .point-buttons {
        display: flex;
        gap: 0.5rem;
        flex-direction: column;
    }

    .point-btn {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        font-size: 1.25rem;
        transition: all 0.3s;
    }

    .point-btn.plus {
        background: var(--google-green);
        color: white;
    }

    .point-btn.plus:hover {
        background: #2d9348;
    }

    .point-btn.minus {
        background: var(--google-red);
        color: white;
    }

    .point-btn.minus:hover {
        background: #d33426;
    }
</style>
@endpush

@section('content')
<div class="content-grid">
    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon blue">👥</div>
            <div class="stat-value">{{ $stats['total_members'] ?? 0 }}</div>
            <div class="stat-label">Total Members</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">⭐</div>
            <div class="stat-value">{{ $stats['total_points'] ?? 0 }}</div>
            <div class="stat-label">Total Points Given</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">📊</div>
            <div class="stat-value">{{ $stats['avg_points'] ?? 0 }}</div>
            <div class="stat-label">Average Points</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">🎯</div>
            <div class="stat-value">{{ $stats['assessments_month'] ?? 0 }}</div>
            <div class="stat-label">Assessments This Month</div>
        </div>
    </div>

    <!-- Member List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Member Assessment</h2>
            <p class="card-subtitle">Click on a member to add points</p>
        </div>

        <!-- Filter -->
        <div class="form-row" style="margin-bottom: 1.5rem;">
            <div class="input-group">
                <label class="label-modern">Filter by Department</label>
                <select class="input-modern" style="padding-left: 1rem;" id="department-filter">
                    <option value="">All Departments</option>
                    @foreach($departments ?? [] as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label class="label-modern">Cari Member</label>
                <div class="input-box">
                    <i data-lucide="search" class="input-icon-left"></i>
                    <input type="text" class="input-modern" placeholder="Cari by name..." id="search-member">
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div style="margin-bottom: 1rem; display: none;" id="bulk-actions-bar">
            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(66, 133, 244, 0.1); border-radius: 12px;">
                <span style="font-weight: 600; color: var(--google-blue);">
                    <span id="selected-count">0</span> member dipilih
                </span>
                <button class="btn btn-primary" style="padding: 0.5rem 1rem;" onclick="openBulkPointModal()">
                    <i data-lucide="users" style="width: 16px; height: 16px;"></i>
                    Beri Poin Massal
                </button>
                <button class="btn btn-secondary" style="padding: 0.5rem 1rem;" onclick="clearSelection()">
                    <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                    Batal
                </button>
            </div>
        </div>

        <table class="member-table">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" style="width: 18px; height: 18px; cursor: pointer;">
                    </th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Total Points</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="member-table-body">
                @foreach($members ?? [] as $member)
                <tr>
                    <td>
                        <input type="checkbox" class="member-checkbox" value="{{ $member->id }}" onchange="updateBulkActions()" style="width: 18px; height: 18px; cursor: pointer;">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="navbar-avatar" style="width: 36px; height: 36px;">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $member->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $member->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-role {{ $member->role }}">{{ str_replace('-', ' ', $member->role) }}</span>
                    </td>
                    <td>{{ $member->department->name ?? '-' }}</td>
                    <td>
                        <span class="points-display {{ $member->total_points >= 0 ? 'positive' : 'negative' }}">
                            {{ $member->total_points }} pts
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-primary" style="padding: 0.5rem 1rem;" onclick="openAddPointModal({{ $member->id }}, '{{ $member->name }}')">
                            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                            Add Points
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Bulk Add Point Modal -->
<div class="modal" id="bulk-point-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Beri Poin Massal</h3>
            <button class="modal-close" onclick="closeBulkPointModal()">×</button>
        </div>

        <div style="text-align: center; margin-bottom: 1.5rem; padding: 1rem; background: rgba(66, 133, 244, 0.1); border-radius: 12px;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">👥</div>
            <div style="font-weight: 600; color: var(--google-blue);">
                <span id="bulk-selected-count">0</span> member akan menerima poin
            </div>
        </div>

        <form id="bulk-point-form">
            <div class="input-group">
                <label class="label-modern">Kategori<span class="required">*</span></label>
                <div class="category-grid">
                    <div class="category-card bulk-category" data-category="commitment" data-min="1" data-max="10">
                        <div class="category-emoji">💪</div>
                        <div class="category-name">Commitment</div>
                        <div class="category-range">+1 s/d +10</div>
                    </div>
                    <div class="category-card bulk-category" data-category="collaboration" data-min="1" data-max="10">
                        <div class="category-emoji">🤝</div>
                        <div class="category-name">Collaboration</div>
                        <div class="category-range">+1 s/d +10</div>
                    </div>
                    <div class="category-card bulk-category" data-category="initiative" data-min="1" data-max="15">
                        <div class="category-emoji">💡</div>
                        <div class="category-name">Initiative</div>
                        <div class="category-range">+1 s/d +15</div>
                    </div>
                    <div class="category-card bulk-category" data-category="responsibility" data-min="1" data-max="10">
                        <div class="category-emoji">✅</div>
                        <div class="category-name">Responsibility</div>
                        <div class="category-range">+1 s/d +10</div>
                    </div>
                    <div class="category-card bulk-category" data-category="violation" data-min="-10" data-max="-1">
                        <div class="category-emoji">⚠️</div>
                        <div class="category-name">Violation</div>
                        <div class="category-range">-10 s/d -1</div>
                    </div>
                </div>
                <input type="hidden" id="bulk-category-input" required>
            </div>

            <div class="input-group">
                <label class="label-modern">Poin<span class="required">*</span></label>
                <div class="point-input-group">
                    <div class="point-input-wrapper">
                        <input type="number" class="point-input" id="bulk-point-value" value="1" min="1" max="10" required>
                        <div style="text-align: center; margin-top: 0.5rem; font-size: 0.75rem; color: var(--text-secondary);" id="bulk-point-range-hint">
                            Pilih kategori terlebih dahulu
                        </div>
                    </div>
                    <div class="point-buttons">
                        <button type="button" class="point-btn plus" onclick="incrementBulkPoint()">+</button>
                        <button type="button" class="point-btn minus" onclick="decrementBulkPoint()">−</button>
                    </div>
                </div>
            </div>

            <div class="input-group">
                <label class="label-modern">Catatan<span class="required">*</span></label>
                <textarea class="input-modern" style="padding: 1rem; resize: vertical; min-height: 100px;" id="bulk-note-input" placeholder="Jelaskan alasan penilaian ini..." required></textarea>
            </div>

            <div class="submit-wrapper" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem;">
                    <i data-lucide="users-check" style="width: 20px; height: 20px;"></i>
                    Berikan Poin ke <span id="bulk-submit-count">0</span> Member
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Point Modal -->
<div class="modal" id="add-point-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Add Points</h3>
            <button class="modal-close" onclick="closeAddPointModal()">×</button>
        </div>

        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div class="navbar-avatar" style="width: 60px; height: 60px; margin: 0 auto 1rem; font-size: 1.5rem;">
                <span id="modal-user-initial"></span>
            </div>
            <div style="font-weight: 600; font-size: 1.125rem;" id="modal-user-name"></div>
        </div>

        <form id="add-point-form">
            <input type="hidden" id="modal-user-id">

            <div class="input-group">
                <label class="label-modern">Category<span class="required">*</span></label>
                <div class="category-grid">
                    <div class="category-card" data-category="commitment" data-min="1" data-max="10">
                        <div class="category-emoji">💪</div>
                        <div class="category-name">Commitment</div>
                        <div class="category-range">+1 to +10</div>
                    </div>
                    <div class="category-card" data-category="collaboration" data-min="1" data-max="10">
                        <div class="category-emoji">🤝</div>
                        <div class="category-name">Collaboration</div>
                        <div class="category-range">+1 to +10</div>
                    </div>
                    <div class="category-card" data-category="initiative" data-min="1" data-max="15">
                        <div class="category-emoji">💡</div>
                        <div class="category-name">Initiative</div>
                        <div class="category-range">+1 to +15</div>
                    </div>
                    <div class="category-card" data-category="responsibility" data-min="1" data-max="10">
                        <div class="category-emoji">✅</div>
                        <div class="category-name">Responsibility</div>
                        <div class="category-range">+1 to +10</div>
                    </div>
                    <div class="category-card" data-category="violation" data-min="-10" data-max="-1">
                        <div class="category-emoji">⚠️</div>
                        <div class="category-name">Violation</div>
                        <div class="category-range">-10 to -1</div>
                    </div>
                </div>
                <input type="hidden" id="category-input" required>
            </div>

            <div class="input-group">
                <label class="label-modern">Points<span class="required">*</span></label>
                <div class="point-input-group">
                    <div class="point-input-wrapper">
                        <input type="number" class="point-input" id="point-value" value="1" min="1" max="10" required>
                        <div style="text-align: center; margin-top: 0.5rem; font-size: 0.75rem; color: var(--text-secondary);" id="point-range-hint">
                            Select category first
                        </div>
                    </div>
                    <div class="point-buttons">
                        <button type="button" class="point-btn plus" onclick="incrementPoint()">+</button>
                        <button type="button" class="point-btn minus" onclick="decrementPoint()">−</button>
                    </div>
                </div>
            </div>

            <div class="input-group">
                <label class="label-modern">Notes<span class="required">*</span></label>
                <textarea class="input-modern" style="padding: 1rem; resize: vertical; min-height: 100px;" id="note-input" placeholder="Explain the reason for this assessment..." required></textarea>
            </div>

            <div class="submit-wrapper" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem;">
                    <i data-lucide="check" style="width: 20px; height: 20px;"></i>
                    Kirim Points
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentCategory = null;
    let currentMin = 1;
    let currentMax = 10;

    let bulkCategory = null;
    let bulkMin = 1;
    let bulkMax = 10;

    // Bulk selection functions
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateBulkActions();
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
        const count = checkedBoxes.length;
        const bulkBar = document.getElementById('bulk-actions-bar');
        const selectAll = document.getElementById('select-all');

        document.getElementById('selected-count').textContent = count;

        if (count > 0) {
            bulkBar.style.display = 'block';
        } else {
            bulkBar.style.display = 'none';
        }

        // Update select-all checkbox state
        const allCheckboxes = document.querySelectorAll('.member-checkbox');
        selectAll.checked = count === allCheckboxes.length;
        selectAll.indeterminate = count > 0 && count < allCheckboxes.length;
    }

    function clearSelection() {
        document.querySelectorAll('.member-checkbox').forEach(cb => {
            cb.checked = false;
        });
        document.getElementById('select-all').checked = false;
        updateBulkActions();
    }

    function openBulkPointModal() {
        const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
        const count = checkedBoxes.length;

        if (count === 0) {
            alert('Pilih minimal 1 member terlebih dahulu');
            return;
        }

        document.getElementById('bulk-selected-count').textContent = count;
        document.getElementById('bulk-submit-count').textContent = count;
        document.getElementById('bulk-point-modal').classList.add('active');

        // Reset form
        document.getElementById('bulk-point-form').reset();
        document.querySelectorAll('.bulk-category').forEach(c => c.classList.remove('active'));
        document.getElementById('bulk-point-range-hint').textContent = 'Pilih kategori terlebih dahulu';

        lucide.createIcons();
    }

    function closeBulkPointModal() {
        document.getElementById('bulk-point-modal').classList.remove('active');
    }

    // Bulk category selection
    document.querySelectorAll('.bulk-category').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.bulk-category').forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            bulkCategory = this.dataset.category;
            bulkMin = parseInt(this.dataset.min);
            bulkMax = parseInt(this.dataset.max);

            document.getElementById('bulk-category-input').value = bulkCategory;
            document.getElementById('bulk-point-value').min = bulkMin;
            document.getElementById('bulk-point-value').max = bulkMax;
            document.getElementById('bulk-point-value').value = bulkMin > 0 ? bulkMin : bulkMax;

            document.getElementById('bulk-point-range-hint').textContent = `Range: ${bulkMin} s/d ${bulkMax}`;

            lucide.createIcons();
        });
    });

    function incrementBulkPoint() {
        const input = document.getElementById('bulk-point-value');
        let val = parseInt(input.value);
        if (val < bulkMax) {
            input.value = val + 1;
        }
    }

    function decrementBulkPoint() {
        const input = document.getElementById('bulk-point-value');
        let val = parseInt(input.value);
        if (val > bulkMin) {
            input.value = val - 1;
        }
    }

    // Bulk form submission
    document.getElementById('bulk-point-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
        const userIds = Array.from(checkedBoxes).map(cb => cb.value);
        const category = document.getElementById('bulk-category-input').value;
        const value = document.getElementById('bulk-point-value').value;
        const note = document.getElementById('bulk-note-input').value;

        if (!category) {
            alert('Pilih kategori terlebih dahulu');
            return;
        }

        if (userIds.length === 0) {
            alert('Tidak ada member yang dipilih');
            return;
        }

        if (!confirm(`Apakah kamu yakin ingin memberikan ${value} poin (${category}) kepada ${userIds.length} member?`)) {
            return;
        }

        showLoading();

        try {
            const response = await fetch('{{ route('hr.points.bulk') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_ids: userIds,
                    category: category,
                    value: parseInt(value),
                    note: note
                })
            });

            const data = await response.json();

            hideLoading();

            if (response.ok && data.success) {
                let message = data.message;
                if (data.errors && data.errors.length > 0) {
                    message += '\n\nBeberapa member gagal diproses:\n';
                    data.errors.forEach(err => {
                        message += `- User ID ${err.user_id}: ${err.error}\n`;
                    });
                }
                alert(message);
                closeBulkPointModal();
                clearSelection();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Gagal memberikan poin'));
            }
        } catch (error) {
            hideLoading();
            alert('Terjadi kesalahan. Silakan coba lagi.');
            console.error(error);
        }
    });

    // Category selection (single point modal)
    document.querySelectorAll('.category-card:not(.bulk-category)').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.category-card:not(.bulk-category)').forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            currentCategory = this.dataset.category;
            currentMin = parseInt(this.dataset.min);
            currentMax = parseInt(this.dataset.max);

            document.getElementById('category-input').value = currentCategory;
            document.getElementById('point-value').min = currentMin;
            document.getElementById('point-value').max = currentMax;
            document.getElementById('point-value').value = currentMin > 0 ? currentMin : currentMax;

            document.getElementById('point-range-hint').textContent = `Range: ${currentMin} to ${currentMax}`;

            lucide.createIcons();
        });
    });

    function incrementPoint() {
        const input = document.getElementById('point-value');
        let val = parseInt(input.value);
        if (val < currentMax) {
            input.value = val + 1;
        }
    }

    function decrementPoint() {
        const input = document.getElementById('point-value');
        let val = parseInt(input.value);
        if (val > currentMin) {
            input.value = val - 1;
        }
    }

    function openAddPointModal(userId, userName) {
        document.getElementById('modal-user-id').value = userId;
        document.getElementById('modal-user-name').textContent = userName;
        document.getElementById('modal-user-initial').textContent = userName.charAt(0).toUpperCase();
        document.getElementById('add-point-modal').classList.add('active');

        // Reset form
        document.getElementById('add-point-form').reset();
        document.querySelectorAll('.category-card').forEach(c => c.classList.remove('active'));
        document.getElementById('point-range-hint').textContent = 'Select category first';

        lucide.createIcons();
    }

    function closeAddPointModal() {
        document.getElementById('add-point-modal').classList.remove('active');
    }

    // Form submission
    document.getElementById('add-point-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const userId = document.getElementById('modal-user-id').value;
        const category = document.getElementById('category-input').value;
        const value = document.getElementById('point-value').value;
        const note = document.getElementById('note-input').value;

        if (!category) {
            alert('Please select a category');
            return;
        }

        showLoading();

        try {
            const response = await fetch('{{ route('hr.points.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
                    category: category,
                    value: parseInt(value),
                    note: note
                })
            });

            const data = await response.json();

            hideLoading();

            if (response.ok && data.success) {
                alert('Points added successfully!' + (data.new_badges && data.new_badges.length > 0 ? '\n\nNew badges earned!' : ''));
                closeAddPointModal();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to add points'));
            }
        } catch (error) {
            hideLoading();
            alert('An error occurred. Please try again.');
            console.error(error);
        }
    });

    // Initialize icons
    lucide.createIcons();
</script>
@endpush
