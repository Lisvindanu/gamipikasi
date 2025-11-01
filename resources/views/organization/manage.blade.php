@extends('layouts.app')

@section('title', 'Manage Struktur Organisasi')

@section('content')
<style>
    .org-manage-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .org-header {
        margin-bottom: 2rem;
    }

    .org-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .org-header p {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .success-alert {
        background: rgba(52, 168, 83, 0.1);
        border: 1px solid var(--google-green);
        color: var(--google-green);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .add-btn {
        background: linear-gradient(135deg, var(--google-blue), #1976d2);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(66, 133, 244, 0.3);
    }

    .members-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .member-card {
        background: white;
        border: 2px solid #e8eaed;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s;
    }

    .member-card:hover {
        border-color: var(--google-blue);
        box-shadow: 0 4px 12px rgba(66, 133, 244, 0.15);
        transform: translateY(-2px);
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .member-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .member-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .member-details {
        flex: 1;
        min-width: 0;
    }

    .member-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .member-position {
        font-size: 0.875rem;
        color: var(--google-blue);
        font-weight: 500;
    }

    .member-order {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .member-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit {
        flex: 1;
        background: #fbbc04;
        color: white;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        background: #f9ab00;
    }

    .btn-remove {
        flex: 1;
        background: var(--google-red);
        color: white;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-remove:hover {
        background: #d93025;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e8eaed;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--google-blue);
        box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
    }

    .modal-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-cancel {
        flex: 1;
        padding: 0.75rem 1.5rem;
        background: #f1f3f4;
        color: var(--text-primary);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #e8eaed;
    }

    .btn-submit {
        flex: 1;
        padding: 0.75rem 1.5rem;
        background: var(--google-blue);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #1976d2;
    }

    @media (max-width: 768px) {
        .org-manage-container {
            padding: 1rem;
        }

        .org-header h1 {
            font-size: 1.5rem;
        }

        .members-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            padding: 1.5rem;
        }
    }
</style>

<div class="org-manage-container">
    <!-- Header -->
    <div class="org-header">
        <h1>Manage Struktur Organisasi</h1>
        <p>Kelola struktur organisasi GDG on Campus</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-alert">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Add Button -->
    <button onclick="openAddModal()" class="add-btn">
        <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
        Tambah Posisi
    </button>

    <!-- Members Section -->
    <div class="members-section">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
            Anggota Organisasi
        </h2>
        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
            {{ $members->count() }} anggota terdaftar
        </p>

        <div class="members-grid">
            @forelse($members as $member)
                <div class="member-card">
                    <div class="member-info">
                        <div class="member-avatar">
                            @if($member->avatar_path)
                                <img src="{{ asset('storage/' . $member->avatar_path) }}" alt="{{ $member->name }}">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="member-details">
                            <div class="member-name" title="{{ $member->name }}">{{ $member->name }}</div>
                            <div class="member-position">{{ $member->organization_display_name }}</div>
                            <div class="member-order">Order: {{ $member->organization_order }}</div>
                        </div>
                    </div>

                    <div class="member-actions">
                        <button onclick='openEditModal(@json($member->id), "{{ $member->organization_position }}", {{ $member->organization_order }})' class="btn-edit">
                            Edit
                        </button>
                        <form action="{{ route('organization.destroy', $member) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Yakin hapus posisi {{ $member->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-remove" style="width: 100%;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i data-lucide="users" style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;"></i>
                    <p style="font-size: 1rem; color: var(--text-secondary);">Belum ada anggota organisasi yang terdaftar</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <h3 class="modal-header">Tambah Posisi Baru</h3>

        <form action="{{ route('organization.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">User</label>
                <select name="user_id" required class="form-select">
                    <option value="">Pilih User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Posisi</label>
                <select name="position_name" required class="form-select">
                    <option value="">Pilih Posisi</option>
                    <optgroup label="Core Leadership">
                        <option value="lead">Lead</option>
                        <option value="co_lead">Co-Lead</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="secretary">Secretary</option>
                    </optgroup>
                    <optgroup label="Department Heads">
                        <option value="head_of_human_resource">Head of Human Resource</option>
                        <option value="head_of_event">Head of Event</option>
                        <option value="head_of_public_relation">Head of Public Relation</option>
                        <option value="head_of_media_creative">Head of Media Creative</option>
                        <option value="head_of_machine_learning">Head of Machine Learning</option>
                        <option value="head_of_web_developer">Head of Web Developer</option>
                        <option value="head_of_curriculum_developer">Head of Curriculum Developer</option>
                        <option value="head_of_game_development">Head of Game Development</option>
                        <option value="head_of_iot_development">Head of IoT Development</option>
                    </optgroup>
                    <optgroup label="Department Staff">
                        <option value="staff_hr">Staff HR</option>
                        <option value="staff_event">Staff Event</option>
                        <option value="staff_pr">Staff Public Relation</option>
                        <option value="staff_media">Staff Media Creative</option>
                        <option value="staff_web_developer">Staff Web Developer</option>
                        <option value="staff_ml">Staff Machine Learning</option>
                        <option value="staff_iot">Staff IoT</option>
                        <option value="staff_game">Staff Game Development</option>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Order (urutan tampilan)</label>
                <input type="number" name="order" value="0" class="form-input">
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeAddModal()" class="btn-cancel">
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <h3 class="modal-header">Edit Posisi</h3>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Posisi</label>
                <select name="position_name" id="edit_position_name" required class="form-select">
                    <optgroup label="Core Leadership">
                        <option value="lead">Lead</option>
                        <option value="co_lead">Co-Lead</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="secretary">Secretary</option>
                    </optgroup>
                    <optgroup label="Department Heads">
                        <option value="head_of_human_resource">Head of Human Resource</option>
                        <option value="head_of_event">Head of Event</option>
                        <option value="head_of_public_relation">Head of Public Relation</option>
                        <option value="head_of_media_creative">Head of Media Creative</option>
                        <option value="head_of_machine_learning">Head of Machine Learning</option>
                        <option value="head_of_web_developer">Head of Web Developer</option>
                        <option value="head_of_curriculum_developer">Head of Curriculum Developer</option>
                        <option value="head_of_game_development">Head of Game Development</option>
                        <option value="head_of_iot_development">Head of IoT Development</option>
                    </optgroup>
                    <optgroup label="Department Staff">
                        <option value="staff_hr">Staff HR</option>
                        <option value="staff_event">Staff Event</option>
                        <option value="staff_pr">Staff Public Relation</option>
                        <option value="staff_media">Staff Media Creative</option>
                        <option value="staff_web_developer">Staff Web Developer</option>
                        <option value="staff_ml">Staff Machine Learning</option>
                        <option value="staff_iot">Staff IoT</option>
                        <option value="staff_game">Staff Game Development</option>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Order (urutan tampilan)</label>
                <input type="number" name="order" id="edit_order" class="form-input">
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeEditModal()" class="btn-cancel">
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddModal() {
    document.getElementById('addModal').classList.remove('active');
}

function openEditModal(userId, position, order) {
    const form = document.getElementById('editForm');
    form.action = `/organization/${userId}`;

    document.getElementById('edit_position_name').value = position;
    document.getElementById('edit_order').value = order;

    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
}
</script>
@endsection
