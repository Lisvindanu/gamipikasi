@extends('layouts.app')

@section('title', 'User Management')

@push('styles')
<style>
    .users-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--text-secondary);
    }

    .users-grid {
        display: grid;
        gap: 1.5rem;
    }

    .user-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4285f4, #34a853);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .user-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .user-meta {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .role-badge {
        background: rgba(66, 133, 244, 0.1);
        color: #4285f4;
    }

    .role-badge.lead { background: rgba(234, 67, 53, 0.1); color: #ea4335; }
    .role-badge.head { background: rgba(251, 188, 5, 0.1); color: #fbbc05; }
    .role-badge.member { background: rgba(52, 168, 83, 0.1); color: #34a853; }

    .dept-badge {
        background: rgba(0, 0, 0, 0.05);
        color: var(--text-secondary);
    }

    .user-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #4285f4;
        color: white;
    }

    .btn-primary:hover {
        background: #3367d6;
    }

    .btn-secondary {
        background: rgba(0, 0, 0, 0.05);
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background: rgba(0, 0, 0, 0.1);
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
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        box-shadow: var(--shadow-xl);
    }

    .modal-header {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-group select:focus {
        outline: none;
        border-color: #4285f4;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<div class="users-container">
    <div class="page-header">
        <h1>User Management</h1>
        <p>Manage user roles and departments</p>
    </div>

    {{-- Statistics --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $users->count() }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $users->where('role', 'head')->count() }}</div>
            <div class="stat-label">Department Heads</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $users->where('role', 'member')->count() }}</div>
            <div class="stat-label">Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $departments->count() }}</div>
            <div class="stat-label">Departments</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-section">
        <div class="filter-grid">
            <div class="form-group">
                <label>Filter by Role</label>
                <select id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="lead">Lead</option>
                    <option value="co-lead">Co-Lead</option>
                    <option value="secretary">Secretary</option>
                    <option value="bendahara">Bendahara</option>
                    <option value="head">Head</option>
                    <option value="member">Member</option>
                </select>
            </div>
            <div class="form-group">
                <label>Filter by Department</label>
                <select id="deptFilter">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                    <option value="null">No Department</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Users List --}}
    <div class="users-grid" id="usersList">
        @foreach($users as $user)
        <div class="user-card"
             data-role="{{ $user->role }}"
             data-dept="{{ $user->department_id ?? 'null' }}">
            <div class="user-avatar">
                {{ substr($user->name, 0, 1) }}
            </div>

            <div class="user-info">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email">{{ $user->email }}</div>
                <div class="user-meta">
                    <span class="user-badge role-badge {{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->department)
                        <span class="user-badge dept-badge">
                            {{ $user->department->icon }} {{ $user->department->name }}
                        </span>
                    @else
                        <span class="user-badge dept-badge">
                            @if($user->role === 'head')
                                🎓 Head of Curriculum
                            @else
                                No Department
                            @endif
                        </span>
                    @endif
                </div>
            </div>

            <div class="user-actions">
                <button class="btn btn-primary" onclick="editRole({{ $user->id }}, '{{ $user->role }}')">
                    Edit Role
                </button>
                <button class="btn btn-secondary" onclick="editDepartment({{ $user->id }}, {{ $user->department_id ?? 'null' }})">
                    Edit Dept
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Edit Role Modal --}}
<div id="roleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Edit User Role</div>
        <form id="roleForm">
            <input type="hidden" id="roleUserId">
            <div class="form-group">
                <label>Select Role</label>
                <select id="roleSelect">
                    <option value="lead">Lead</option>
                    <option value="co-lead">Co-Lead</option>
                    <option value="secretary">Secretary</option>
                    <option value="bendahara">Bendahara</option>
                    <option value="head">Head</option>
                    <option value="member">Member</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('roleModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Department Modal --}}
<div id="deptModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Edit User Department</div>
        <form id="deptForm">
            <input type="hidden" id="deptUserId">
            <div class="form-group">
                <label>Select Department</label>
                <select id="deptSelect">
                    <option value="">No Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->icon }} {{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deptModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filters
    document.getElementById('roleFilter').addEventListener('change', filterUsers);
    document.getElementById('deptFilter').addEventListener('change', filterUsers);

    function filterUsers() {
        const roleFilter = document.getElementById('roleFilter').value;
        const deptFilter = document.getElementById('deptFilter').value;
        const cards = document.querySelectorAll('.user-card');

        cards.forEach(card => {
            const role = card.dataset.role;
            const dept = card.dataset.dept;

            const roleMatch = !roleFilter || role === roleFilter;
            const deptMatch = !deptFilter || dept === deptFilter;

            card.style.display = (roleMatch && deptMatch) ? 'grid' : 'none';
        });
    }

    // Edit Role
    function editRole(userId, currentRole) {
        document.getElementById('roleUserId').value = userId;
        document.getElementById('roleSelect').value = currentRole;
        document.getElementById('roleModal').classList.add('active');
    }

    // Edit Department
    function editDepartment(userId, currentDept) {
        document.getElementById('deptUserId').value = userId;
        document.getElementById('deptSelect').value = currentDept || '';
        document.getElementById('deptModal').classList.add('active');
    }

    // Close Modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    // Handle Role Form Kirim
    document.getElementById('roleForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const userId = document.getElementById('roleUserId').value;
        const role = document.getElementById('roleSelect').value;

        try {
            const response = await fetch(`/settings/users/${userId}/role`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ role })
            });

            const data = await response.json();

            if (data.success) {
                alert('User role updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error updating role: ' + error.message);
        }
    });

    // Handle Department Form Kirim
    document.getElementById('deptForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const userId = document.getElementById('deptUserId').value;
        const departmentId = document.getElementById('deptSelect').value || null;

        try {
            const response = await fetch(`/settings/users/${userId}/department`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ department_id: departmentId })
            });

            const data = await response.json();

            if (data.success) {
                alert('User department updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error updating department: ' + error.message);
        }
    });

    // Close modal on background click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
</script>
@endpush
