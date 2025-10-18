@extends('layouts.app')

@section('title', 'Edit Postingan')

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

    .existing-attachments {
        margin-bottom: 1rem;
    }

    .attachment-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--bg-light);
        border-radius: 8px;
        margin-bottom: 0.5rem;
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
    <!-- Back Button -->
    <div style="max-width: 900px; margin: 0 auto 1rem;">
        <a href="{{ route('posts.show', $post->slug) }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Back to Post
        </a>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">Edit Postingan</h1>
            <p class="form-subtitle">Perbarui informasi postingan Anda</p>
        </div>

        @if(session('error'))
            <div style="padding: 1rem; background: rgba(234, 67, 53, 0.1); border-left: 4px solid var(--google-red); border-radius: 8px; margin-bottom: 1.5rem;">
                <strong style="color: var(--google-red);">Error:</strong> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="form-group">
                <label class="form-label">
                    Title<span class="required">*</span>
                </label>
                <input type="text" name="title" class="form-input" placeholder="Enter post title..." value="{{ old('title', $post->title) }}" required>
                @error('title')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Category -->
            <div class="form-group">
                <label class="form-label">
                    Category<span class="required">*</span>
                </label>
                <div class="category-grid">
                    @if(in_array('announcement', $allowedCategories))
                    <div class="category-option">
                        <input type="radio" name="category" value="announcement" id="cat-announcement" {{ old('category', $post->category) === 'announcement' ? 'checked' : '' }} required>
                        <label for="cat-announcement" class="category-label">
                            <span class="category-icon">📢</span>
                            <div class="category-info">
                                <div class="category-name">Announcement</div>
                                <div class="category-desc">Official updates</div>
                            </div>
                        </label>
                    </div>
                    @endif

                    @if(in_array('event', $allowedCategories))
                    <div class="category-option">
                        <input type="radio" name="category" value="event" id="cat-event" {{ old('category', $post->category) === 'event' ? 'checked' : '' }}>
                        <label for="cat-event" class="category-label">
                            <span class="category-icon">🎉</span>
                            <div class="category-info">
                                <div class="category-name">Event</div>
                                <div class="category-desc">Event details</div>
                            </div>
                        </label>
                    </div>
                    @endif

                    @if(in_array('report', $allowedCategories))
                    <div class="category-option">
                        <input type="radio" name="category" value="report" id="cat-report" {{ old('category', $post->category) === 'report' ? 'checked' : '' }}>
                        <label for="cat-report" class="category-label">
                            <span class="category-icon">📊</span>
                            <div class="category-info">
                                <div class="category-name">Report</div>
                                <div class="category-desc">Activity reports</div>
                            </div>
                        </label>
                    </div>
                    @endif

                    @if(in_array('minutes', $allowedCategories))
                    <div class="category-option">
                        <input type="radio" name="category" value="minutes" id="cat-minutes" {{ old('category', $post->category) === 'minutes' ? 'checked' : '' }}>
                        <label for="cat-minutes" class="category-label">
                            <span class="category-icon">📝</span>
                            <div class="category-info">
                                <div class="category-name">Minutes</div>
                                <div class="category-desc">Meeting minutes</div>
                            </div>
                        </label>
                    </div>
                    @endif

                    @if(in_array('regulation', $allowedCategories))
                    <div class="category-option">
                        <input type="radio" name="category" value="regulation" id="cat-regulation" {{ old('category', $post->category) === 'regulation' ? 'checked' : '' }}>
                        <label for="cat-regulation" class="category-label">
                            <span class="category-icon">📋</span>
                            <div class="category-info">
                                <div class="category-name">Regulation</div>
                                <div class="category-desc">Rules & policies</div>
                            </div>
                        </label>
                    </div>
                    @endif

                    @if(in_array('general', $allowedCategories))
                    <div class="category-option">
                        <input type="radio" name="category" value="general" id="cat-general" {{ old('category', $post->category) === 'general' ? 'checked' : '' }}>
                        <label for="cat-general" class="category-label">
                            <span class="category-icon">💬</span>
                            <div class="category-info">
                                <div class="category-name">General</div>
                                <div class="category-desc">Other posts</div>
                            </div>
                        </label>
                    </div>
                    @endif
                </div>
                @error('category')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Content -->
            <div class="form-group">
                <label class="form-label">
                    Content<span class="required">*</span>
                </label>
                <textarea name="content" class="form-textarea" placeholder="Write your post content here..." required>{{ old('content', $post->content) }}</textarea>
                <div class="form-help">Supports plain text and line breaks</div>
                @error('content')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Department (optional) -->
            <div class="form-group">
                <label class="form-label">
                    Department (Optional)
                </label>
                <select name="department_id" class="form-select">
                    <option value="">-- All Departments --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $post->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                <div class="form-help">Associate this post with a specific department</div>
            </div>

            <!-- Visibility -->
            <div class="form-group">
                <label class="form-label">
                    Visibility<span class="required">*</span>
                </label>
                <div class="visibility-options">
                    <div class="visibility-option">
                        <input type="radio" name="visibility" value="public" id="vis-public" {{ old('visibility', $post->visibility) === 'public' ? 'checked' : '' }} required>
                        <label for="vis-public" class="visibility-label">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🌍</div>
                            <div style="font-weight: 600; margin-bottom: 0.25rem;">Public</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Everyone can see</div>
                        </label>
                    </div>

                    <div class="visibility-option">
                        <input type="radio" name="visibility" value="internal" id="vis-internal" {{ old('visibility', $post->visibility) === 'internal' ? 'checked' : '' }}>
                        <label for="vis-internal" class="visibility-label">
                            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🔒</div>
                            <div style="font-weight: 600; margin-bottom: 0.25rem;">Internal</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Core team only</div>
                        </label>
                    </div>
                </div>
                @error('visibility')
                    <div class="form-help" style="color: var(--google-red);">{{ $message }}</div>
                @enderror
            </div>

            <!-- Existing Attachments -->
            @if($post->attachments->count() > 0)
                <div class="form-group">
                    <label class="form-label">Existing Attachments</label>
                    <div class="existing-attachments">
                        @foreach($post->attachments as $attachment)
                            @php
                                $isImage = in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            @endphp

                            @if($isImage)
                                {{-- Image Preview with Delete --}}
                                <div class="attachment-item" style="display: block; padding: 0; position: relative;">
                                    <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                         alt="{{ $attachment->file_name }}"
                                         style="width: 100%; max-height: 300px; object-fit: contain; border-radius: 8px; background: #f5f5f5;">
                                    <div style="padding: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 600; font-size: 0.875rem;">{{ $attachment->file_name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                                        </div>
                                        <button type="button" class="btn btn-sm delete-attachment-btn" style="background: var(--google-red); color: white;" data-attachment-id="{{ $attachment->id }}" data-url="{{ route('posts.attachments.destroy', $attachment) }}">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @else
                                {{-- File with Delete --}}
                                <div class="attachment-item">
                                    <i data-lucide="file" style="width: 20px; height: 20px; color: var(--google-blue);"></i>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 0.875rem;">{{ $attachment->file_name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                                    </div>
                                    <button type="button" class="delete-attachment-btn" style="background: none; border: none; color: var(--google-red); cursor: pointer;" data-attachment-id="{{ $attachment->id }}" data-url="{{ route('posts.attachments.destroy', $attachment) }}">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New Attachments -->
            <div class="form-group">
                <label class="form-label">
                    Add New Attachments (Optional)
                </label>
                <div class="file-upload" onclick="document.getElementById('file-input').click()">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📎</div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">Click to upload files</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">PDF, Images, Documents (Max 10MB each)</div>
                    <input type="file" name="attachments[]" id="file-input" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                </div>
                <div id="file-list" class="file-list"></div>
                <div class="form-help">You can upload multiple files</div>
            </div>

            <!-- Kirim Buttons -->
            <div class="btn-group">
                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-secondary" style="padding: 0.75rem 1.5rem;">
                    <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-success" style="padding: 0.75rem 2rem; margin-left: auto;">
                    <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                    Perbarui Postingan
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

            lucide.createIcons();
        }
    });

    // Delete attachment via AJAX
    document.querySelectorAll('.delete-attachment-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            if (!confirm('Apakah Anda yakin ingin menghapus lampiran ini?')) {
                return;
            }

            const url = this.dataset.url;
            const attachmentId = this.dataset.attachmentId;

            // Create form data with CSRF and method spoofing
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || response.ok) {
                    // Remove the attachment element from DOM
                    this.closest('.attachment-item').remove();
                    alert('Lampiran berhasil dihapus!');
                } else {
                    alert('Gagal menghapus lampiran: ' + (data.message || 'Kesalahan tidak diketahui'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to page reload
                window.location.reload();
            });
        });
    });

    // Initialize icons
    lucide.createIcons();
</script>
@endpush
