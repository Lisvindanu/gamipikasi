@extends('layouts.app')

@section('title', 'Tambah Staff Baru')

@section('content')
<style>
    .create-staff-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e8eaed;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-label.required::after {
        content: '*';
        color: var(--google-red);
        margin-left: 0.25rem;
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

    .form-input.error,
    .form-select.error {
        border-color: var(--google-red);
    }

    .error-message {
        color: var(--google-red);
        font-size: 0.8125rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-hint {
        color: var(--text-secondary);
        font-size: 0.8125rem;
        margin-top: 0.5rem;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        margin: 0;
    }

    .conditional-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
        display: none;
    }

    .conditional-section.active {
        display: block;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e8eaed;
    }

    .btn-cancel {
        flex: 1;
        padding: 0.875rem 1.5rem;
        background: #f1f3f4;
        color: var(--text-primary);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-cancel:hover {
        background: #e8eaed;
    }

    .btn-submit {
        flex: 2;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, var(--google-blue), #1976d2);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(66, 133, 244, 0.3);
    }

    .alert-error {
        background: rgba(234, 67, 53, 0.1);
        border: 1px solid var(--google-red);
        color: var(--google-red);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .create-staff-container {
            margin: 1rem auto;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .form-card {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit {
            order: -1;
        }
    }
</style>

<div class="create-staff-container">
    <!-- Header -->
    <div class="page-header">
        <h1>Tambah Staff Baru</h1>
        <p>Buat akun untuk staff yang baru bergabung dengan GDG on Campus</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert-error">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin: 0.5rem 0 0 1.25rem; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="form-card">
        <form action="{{ route('hr.staff.store') }}" method="POST">
            @csrf

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="form-section-title">Informasi Pribadi</h3>

                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                        placeholder="Contoh: Budi Santoso"
                        required
                    >
                    @error('name')
                        <div class="error-message">
                            <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="nama@student.unpas.ac.id"
                        required
                    >
                    <div class="form-hint">Email akan digunakan untuk login</div>
                    @error('email')
                        <div class="error-message">
                            <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Account Security Section -->
            <div class="form-section">
                <h3 class="form-section-title">Keamanan Akun</h3>

                <div class="form-group">
                    <label class="form-label required">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="Minimal 8 karakter"
                        required
                    >
                    <div class="form-hint">Password minimal 8 karakter</div>
                    @error('password')
                        <div class="error-message">
                            <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Konfirmasi Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-input"
                        placeholder="Ketik ulang password"
                        required
                    >
                </div>
            </div>

            <!-- Department Assignment Section -->
            <div class="form-section">
                <h3 class="form-section-title">Penugasan Departemen</h3>

                <div class="form-group">
                    <label class="form-label required">Departemen</label>
                    <select
                        name="department_id"
                        class="form-select {{ $errors->has('department_id') ? 'error' : '' }}"
                        required
                    >
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="error-message">
                            <i data-lucide="alert-circle" style="width: 14px; height: 14px;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Organization Position Section (Optional) -->
            <div class="form-section">
                <h3 class="form-section-title">Struktur Organisasi (Opsional)</h3>

                <div class="checkbox-group">
                    <input
                        type="checkbox"
                        id="add_to_organization"
                        name="add_to_organization"
                        value="1"
                        {{ old('add_to_organization') ? 'checked' : '' }}
                        onchange="toggleOrganizationFields()"
                    >
                    <label for="add_to_organization">
                        Tambahkan ke struktur organisasi
                    </label>
                </div>

                <div id="organizationFields" class="conditional-section {{ old('add_to_organization') ? 'active' : '' }}">
                    <div class="form-group">
                        <label class="form-label">Posisi Organisasi</label>
                        <select
                            name="organization_position"
                            class="form-select"
                            id="organization_position"
                        >
                            <option value="">Pilih Posisi</option>
                            <optgroup label="Department Staff">
                                <option value="staff_hr" {{ old('organization_position') == 'staff_hr' ? 'selected' : '' }}>Staff HR</option>
                                <option value="staff_event" {{ old('organization_position') == 'staff_event' ? 'selected' : '' }}>Staff Event</option>
                                <option value="staff_pr" {{ old('organization_position') == 'staff_pr' ? 'selected' : '' }}>Staff Public Relation</option>
                                <option value="staff_media" {{ old('organization_position') == 'staff_media' ? 'selected' : '' }}>Staff Media Creative</option>
                                <option value="staff_web_developer" {{ old('organization_position') == 'staff_web_developer' ? 'selected' : '' }}>Staff Web Developer</option>
                                <option value="staff_ml" {{ old('organization_position') == 'staff_ml' ? 'selected' : '' }}>Staff Machine Learning</option>
                                <option value="staff_iot" {{ old('organization_position') == 'staff_iot' ? 'selected' : '' }}>Staff IoT</option>
                                <option value="staff_game" {{ old('organization_position') == 'staff_game' ? 'selected' : '' }}>Staff Game Development</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Order (Urutan Tampilan)</label>
                        <input
                            type="number"
                            name="organization_order"
                            value="{{ old('organization_order', 999) }}"
                            class="form-input"
                            min="1"
                            id="organization_order"
                        >
                        <div class="form-hint">Tentukan urutan tampilan di struktur organisasi (angka lebih kecil = lebih atas)</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('hr.dashboard') }}" class="btn-cancel">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                    Tambah Staff
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleOrganizationFields() {
    const checkbox = document.getElementById('add_to_organization');
    const fields = document.getElementById('organizationFields');
    const positionSelect = document.getElementById('organization_position');
    const orderInput = document.getElementById('organization_order');

    if (checkbox.checked) {
        fields.classList.add('active');
        positionSelect.required = true;
        orderInput.required = true;
    } else {
        fields.classList.remove('active');
        positionSelect.required = false;
        orderInput.required = false;
    }
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    if (typeof window.initLucideIcons === 'function') {
        window.initLucideIcons();
    } else if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}
</script>
@endsection
