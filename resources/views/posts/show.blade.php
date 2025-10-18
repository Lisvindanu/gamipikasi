@extends('layouts.app')

@section('title', $post->title)

@push('styles')
<style>
    .post-container {
        max-width: 900px;
        margin: 2rem auto;
    }

    .post-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }

    .post-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--border-color);
    }

    .post-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .post-meta {
        flex: 1;
    }

    .post-author {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .post-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .post-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .post-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        line-height: 1.3;
    }

    .post-content {
        font-size: 1rem;
        line-height: 1.8;
        color: var(--text-primary);
        margin-bottom: 2rem;
        white-space: pre-wrap;
    }

    .post-attachments {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .attachment-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
    }

    .attachment-item:hover {
        box-shadow: var(--shadow-md);
        transform: translateX(4px);
    }

    .post-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
    }

    .comments-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .comments-header {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .comment-form {
        margin-bottom: 2rem;
    }

    .comment-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .comment-content {
        flex: 1;
    }

    .comment-author {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .comment-time {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .comment-text {
        font-size: 0.875rem;
        line-height: 1.6;
        color: var(--text-primary);
    }

    .alert-success {
        padding: 1rem;
        background: rgba(52, 168, 83, 0.1);
        border-left: 4px solid var(--google-green);
        border-radius: 8px;
        margin-bottom: 1.5rem;
        color: var(--google-green);
    }

    .alert-error {
        padding: 1rem;
        background: rgba(234, 67, 53, 0.1);
        border-left: 4px solid var(--google-red);
        border-radius: 8px;
        margin-bottom: 1.5rem;
        color: var(--google-red);
    }
</style>
@endpush

@section('content')
<div class="post-container">
    <!-- Back Button -->
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('posts.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Kembali ke Postingan
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Post Card -->
    <article class="post-card">
        <!-- Post Header -->
        <div class="post-header">
            <div class="post-avatar">
                {{ strtoupper(substr($post->author->name, 0, 1)) }}
            </div>
            <div class="post-meta">
                <div class="post-author">{{ $post->author->name }}</div>
                <div class="post-info">
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                    <span>•</span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                    @if($post->department)
                        <span>•</span>
                        <span>{{ $post->department->name }}</span>
                    @endif
                    <span>•</span>
                    <span class="post-badge" style="background: rgba(66, 133, 244, 0.1); color: var(--google-blue);">
                        {{ ucfirst($post->category) }}
                    </span>
                    @if($post->visibility === 'internal')
                        <span class="post-badge" style="background: rgba(234, 67, 53, 0.1); color: var(--google-red);">
                            🔒 Internal
                        </span>
                    @endif
                    @if($post->is_pinned)
                        <span class="post-badge" style="background: rgba(251, 188, 5, 0.1); color: #f9ab00;">
                            📌 Pinned
                        </span>
                    @endif
                </div>
            </div>

            @auth
                @if($post->canEdit(Auth::user()))
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                            <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                            Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="padding: 0.5rem 1rem; background: var(--google-red); color: white;">
                                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Post Title -->
        <h1 class="post-title">{{ $post->title }}</h1>

        <!-- Post Content -->
        <div class="post-content">{{ $post->content }}</div>

        <!-- Attachments -->
        @if($post->attachments->count() > 0)
            <div class="post-attachments">
                <h3 style="font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="paperclip" style="width: 20px; height: 20px;"></i>
                    Lampiran ({{ $post->attachments->count() }})
                </h3>
                @foreach($post->attachments as $attachment)
                    @php
                        $isImage = in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                    @endphp

                    @if($isImage)
                        {{-- Image Preview --}}
                        <div class="attachment-item" style="display: block; padding: 0; overflow: hidden;">
                            <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                 alt="{{ $attachment->file_name }}"
                                 style="width: 100%; height: auto; border-radius: 8px; cursor: pointer;"
                                 onclick="window.open('{{ asset('storage/' . $attachment->file_path) }}', '_blank')">
                            <div style="padding: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-weight: 600; font-size: 0.875rem;">{{ $attachment->file_name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                                </div>
                                <a href="{{ route('posts.attachments.download', $attachment) }}" class="btn btn-sm btn-secondary">
                                    <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                                    Unduh
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- File Download Link --}}
                        <a href="{{ route('posts.attachments.download', $attachment) }}" class="attachment-item" style="text-decoration: none; color: inherit;">
                            <i data-lucide="file" style="width: 24px; height: 24px; color: var(--google-blue);"></i>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.875rem;">{{ $attachment->file_name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                            </div>
                            <i data-lucide="download" style="width: 20px; height: 20px; color: var(--text-secondary);"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </article>

    <!-- Comments Section -->
    <div class="comments-section">
        <h2 class="comments-header">
            <i data-lucide="message-circle" style="width: 24px; height: 24px; vertical-align: middle;"></i>
            Komentar ({{ $post->comments->count() }})
        </h2>

        <!-- Comment Form -->
        @auth
            <div class="comment-form">
                <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <textarea name="comment" class="form-input" style="width: 100%; padding: 1rem; border: 2px solid var(--border-color); border-radius: 12px; min-height: 100px; resize: vertical;" placeholder="Tulis komentar..." required></textarea>
                        @error('comment')
                            <div style="color: var(--google-red); font-size: 0.75rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                        <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                        Kirim Komentar
                    </button>
                </form>
            </div>
        @else
            <div style="text-align: center; padding: 2rem; background: var(--bg-light); border-radius: 12px; margin-bottom: 2rem;">
                <p style="color: var(--text-secondary); margin-bottom: 1rem;">Silakan login untuk berkomentar</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        @endauth

        <!-- Comments List -->
        @forelse($post->comments as $comment)
            <div class="comment-item">
                <div class="comment-avatar">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                <div class="comment-content">
                    <div class="comment-author">{{ $comment->user->name }}</div>
                    <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                    <div class="comment-text">{{ $comment->comment }}</div>

                    @auth
                        @if($comment->canDelete(Auth::user()))
                            <form action="{{ route('posts.comments.destroy', $comment) }}" method="POST" style="margin-top: 0.5rem;" onsubmit="return confirm('Hapus komentar ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--google-red); font-size: 0.75rem; cursor: pointer; font-weight: 600;">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    Hapus
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
                <div>Belum ada komentar. Jadilah yang pertama berkomentar!</div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
