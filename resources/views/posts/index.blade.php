@extends('layouts.public')

@section('title', 'Postingan & Informasi')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Postingan & Informasi</h1>
            <p class="hero-subtitle">Tetap update dengan berita, pengumuman, dan laporan terbaru dari GDGoC</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Cari & Filter Section -->
        <section class="section">
            <!-- Cari Bar -->
            <form method="GET" action="{{ route('posts.index') }}" style="margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px; position: relative;">
                        <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: var(--text-secondary);"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari postingan..."
                            style="width: 100%; padding: 0.75rem 1rem 0.75rem 3rem; border: 2px solid var(--border-color); border-radius: 12px; font-size: 0.875rem;"
                        >
                    </div>
                    <select name="department" style="padding: 0.75rem 1rem; border: 2px solid var(--border-color); border-radius: 12px; font-size: 0.875rem; min-width: 180px;">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                        <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                        Filter
                    </button>
                    @if(request('search') || request('department'))
                        <a href="{{ route('posts.index', ['category' => request('category')]) }}" class="btn btn-secondary" style="padding: 0.75rem 1rem;" title="Reset filter">
                            <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Category Filter -->
            <div class="filter-bar" style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem; flex-wrap: wrap;">
                <a href="{{ route('posts.index', array_filter(['search' => $search ?? null, 'department' => $department ?? null])) }}" class="filter-chip {{ !request('category') ? 'active' : '' }}">
                    Semua
                </a>
                @foreach(config('posts.categories') as $key => $cat)
                    <a href="{{ route('posts.index', array_filter(['category' => $key, 'search' => $search ?? null, 'department' => $department ?? null])) }}" class="filter-chip {{ request('category') === $key ? 'active' : '' }}">
                        {{ $cat['icon'] }} {{ $cat['name'] }}
                    </a>
                @endforeach

                <div style="margin-left: auto;">
                    @auth
                        @if(app(\App\Services\PostService::class)->canCreatePost(Auth::user()))
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                                Buat Post
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="posts-grid" style="display: grid; gap: 1.5rem;">
                @forelse($posts as $post)
                    <article class="post-card" style="background: white; border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-md); position: relative;">
                        @if($post->is_pinned)
                            <div style="position: absolute; top: 1rem; right: 1rem; background: #FBBC05; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                📌 Pinned
                            </div>
                        @endif

                        <!-- Post Header -->
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div class="navbar-avatar" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                {{ strtoupper(substr($post->author->name, 0, 1)) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $post->author->name }}</div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary);">
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                    @if($post->department)
                                        <span>•</span>
                                        <span>{{ $post->department->name }}</span>
                                    @endif
                                    <span>•</span>
                                    <span class="category-badge category-{{ $post->category }}">{{ ucfirst($post->category) }}</span>
                                </div>
                            </div>
                            @if($post->visibility === 'internal')
                                <span style="background: rgba(234, 67, 53, 0.1); color: var(--google-red); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                    🔒 Internal
                                </span>
                            @endif
                        </div>

                        <!-- Post Content -->
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--text-primary);">
                            <a href="{{ route('posts.show', $post->slug) }}" style="color: inherit; text-decoration: none;">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <div style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 1rem;">
                            {{ Str::limit(strip_tags($post->content), 200) }}
                        </div>

                        <!-- Post Footer -->
                        <div style="display: flex; align-items: center; gap: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color); font-size: 0.875rem; color: var(--text-secondary);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                                {{ $post->comments->count() }} Komentar
                            </div>
                            @if($post->attachments->count() > 0)
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i data-lucide="paperclip" style="width: 16px; height: 16px;"></i>
                                    {{ $post->attachments->count() }} Lampiran
                                </div>
                            @endif
                            <a href="{{ route('posts.show', $post->slug) }}" style="margin-left: auto; color: var(--google-blue); font-weight: 600; text-decoration: none;">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 4rem; color: #5f6368;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                        <div style="font-size: 1.125rem;">Belum ada postingan. Jadilah yang pertama untuk memposting!</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div style="margin-top: 2rem;">
                    {{ $posts->links() }}
                </div>
            @endif
        </section>
    </main>

    <style>
        .filter-chip {
            padding: 0.5rem 1rem;
            border-radius: 24px;
            background: white;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .filter-chip:hover {
            border-color: var(--google-blue);
            background: rgba(66, 133, 244, 0.05);
        }

        .filter-chip.active {
            background: var(--google-blue);
            color: white;
            border-color: var(--google-blue);
        }

        .category-badge {
            padding: 0.125rem 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .category-announcement { background: rgba(66, 133, 244, 0.1); color: var(--google-blue); }
        .category-event { background: rgba(52, 168, 83, 0.1); color: var(--google-green); }
        .category-report { background: rgba(251, 188, 5, 0.1); color: #f9ab00; }
        .category-minutes { background: rgba(234, 67, 53, 0.1); color: var(--google-red); }
        .category-regulation { background: rgba(158, 71, 252, 0.1); color: #9e47fc; }
        .category-general { background: rgba(95, 99, 104, 0.1); color: var(--text-secondary); }

        .post-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
            transition: all 0.3s;
        }
    </style>

    <script>
        lucide.createIcons();
    </script>
@endsection
