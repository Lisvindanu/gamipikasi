@extends('layouts.app')

@section('title', 'Create New Post')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        max-width: 900px;
        margin: 2rem auto;
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .form-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .form-label .required {
        color: var(--google-red);
        margin-left: 0.25rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.875rem;
        font-family: "Google Sans", sans-serif;
        transition: all 0.3s;
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
        min-height: 200px;
        line-height: 1.6;
    }

    .form-help {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .category-option {
        position: relative;
    }

    .category-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .category-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .category-option input[type="radio"]:checked + .category-label {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.05);
    }

    .category-label:hover {
        border-color: var(--google-blue);
    }

    .category-icon {
        font-size: 1.5rem;
    }

    .category-info {
        flex: 1;
    }

    .category-name {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .category-desc {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .visibility-options {
        display: flex;
        gap: 1rem;
    }

    .visibility-option {
        flex: 1;
    }

    .visibility-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .visibility-label {
        display: block;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .visibility-option input[type="radio"]:checked + .visibility-label {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.05);
    }

    .visibility-label:hover {
        border-color: var(--google-blue);
    }

    .file-upload {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload:hover {
        border-color: var(--google-blue);
        background: rgba(66, 133, 244, 0.05);
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .file-list {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .file-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--bg-light);
        border-radius: 8px;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--border-color);
    }
</style>
@endpush

@section('content')
<div class="content-grid">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">Buat Postingan Baru</h1>
            <p class="form-subtitle">Bagikan pengumuman, laporan, atau informasi terbaru untuk komunitas GDGoC</p>
        </div>

        @if(session('error'))
            <div style="padding: 1rem; background: rgba(234, 67, 53, 0.1); border-left: 4px solid var(--google-red); border-radius: 8px; margin-bottom: 1.5rem;">
                <strong style="color: var(--google-red);">Error:</strong> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="form-group">
                <label class="form-label">
                    Judul<span class="required">*</span>
                </label>
                <input type="text" name="title" class="form-input" placeholder="Masukkan judul postingan..." value="{{ old('title') }}" required>
                @error('title')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Category -->
            <div class="form-group">
                <label class="form-label">
                    Kategori<span class="required">*</span>
                </label>
                <div class="category-grid">
                    @foreach(config('posts.categories') as $key => $category)
                        @if(in_array($key, $allowedCategories))
                            <div class="category-option">
                                <input type="radio" name="category" value="{{ $key }}" id="cat-{{ $key }}" {{ old('category') === $key ? 'checked' : '' }} required>
                                <label for="cat-{{ $key }}" class="category-label">
                                    <span class="category-icon">{{ $category['icon'] }}</span>
                                    <div class="category-info">
                                        <div class="category-name">{{ $category['name'] }}</div>
                                        <div class="category-desc">{{ $category['description'] }}</div>
                                    </div>
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                @error('category')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Content -->
            <div class="form-group">
                <label class="form-label">
                    Konten<span class="required">*</span>
                </label>
                <textarea name="content" class="form-textarea" placeholder="Tulis konten postingan di sini..." required>{{ old('content') }}</textarea>
                <div class="form-help">Mendukung teks biasa dan line breaks</div>
                @error('content')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Department (optional) -->
            <div class="form-group">
                <label class="form-label">
                    Departemen (Opsional)
                </label>
                <select name="department_id" class="form-select">
                    <option value="">-- Semua Departemen --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                <div class="form-help">Kaitkan postingan dengan departemen tertentu</div>
            </div>

            <!-- Visibility -->
            <div class="form-group">
                <label class="form-label">
                    Visibilitas<span class="required">*</span>
                </label>
                <div class="visibility-options">
                    <div class="visibility-option">
                        <input type="radio" name="visibility" value="public" id="vis-public" {{ old('visibility') === 'public' ? 'checked' : '' }} required>
                        <label for="vis-public" class="visibility-label">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🌍</div>
                            <div style="font-weight: 600; margin-bottom: 0.25rem;">Publik</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Semua orang bisa lihat</div>
                        </label>
                    </div>

                    <div class="visibility-option">
                        <input type="radio" name="visibility" value="internal" id="vis-internal" {{ old('visibility') === 'internal' || !old('visibility') ? 'checked' : '' }}>
                        <label for="vis-internal" class="visibility-label">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🔒</div>
                            <div style="font-weight: 600; margin-bottom: 0.25rem;">Internal</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Hanya core team</div>
                        </label>
                    </div>
                </div>
                @error('visibility')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Attachments -->
            <div class="form-group">
                <label class="form-label">
                    Lampiran (Opsional)
                </label>
                <div class="file-upload" onclick="document.getElementById('file-input').click()">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📎</div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">Klik untuk upload file</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">PDF, Gambar, Dokumen (Maks 10MB per file)</div>
                    <input type="file" name="attachments[]" id="file-input" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                </div>
                <div id="file-list" class="file-list"></div>
                <div class="form-help">Anda bisa upload beberapa file sekaligus</div>
            </div>

            <!-- Kirim Buttons -->
            <div class="btn-group">
                <a href="{{ route('posts.index') }}" class="btn btn-secondary" style="padding: 0.75rem 1.5rem;">
                    <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-success" style="padding: 0.75rem 2rem; margin-left: auto;">
                    <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                    Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File upload preview
    const fileInput = document.getElementById('file-input');
    const fileList = document.getElementById('file-list');

    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';

        if (this.files.length > 0) {
            Array.from(this.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <i data-lucide="file" style="width: 20px; height: 20px; color: var(--google-blue);"></i>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.875rem;">${file.name}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                    </div>
                `;
                fileList.appendChild(fileItem);
            });

            if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        }
    });

    // Initialize icons
    if (typeof window.initLucideIcons === 'function') { window.initLucideIcons(); } else if (typeof lucide !== 'undefined') { lucide.createIcons(); }
</script>
@endpush
