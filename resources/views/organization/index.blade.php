@extends('layouts.public')

@section('title', 'Struktur Organisasi - GDGoC')

@section('content')
    <!-- Hero Section -->
    @include('components.public.hero', [
        'title' => 'Struktur Organisasi',
        'description' => 'Tim Core Team GDG on Campus Universitas Pasundan periode ini'
    ])

    <!-- Main Content -->
    <main class="main-container">
        <!-- Core Team Section -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Tim Kami</h2>
                <p class="section-subtitle">Core Team GDG on Campus Universitas Pasundan</p>
            </div>

            <div class="org-grid">
                @if($members->count() > 0)
                    @foreach($members as $member)
                        <a href="{{ route('profile.show', $member) }}" class="org-card">
                            <div class="org-avatar">
                                @if($member->avatar_path)
                                    <img src="{{ asset('storage/' . $member->avatar_path) }}" alt="{{ $member->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="org-info">
                                <h3 class="org-name">{{ $member->name }}</h3>
                                <p class="org-position">{{ $member->organization_display_name }}</p>
                                @if($member->department)
                                    <p class="org-department">{{ $member->department->name }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                @else
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #5f6368;">
                        <p>Belum ada struktur organisasi yang terdaftar.</p>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@push('styles')
<style>
    .org-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .org-card {
        background: white;
        border: 1px solid #e8eaed;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .org-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        border-color: #4285F4;
    }

    .org-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 3px solid #e8eaed;
        transition: border-color 0.3s ease;
    }

    .org-card:hover .org-avatar {
        border-color: #4285F4;
    }

    .org-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #4285F4, #34A853);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 600;
        color: white;
    }

    .org-info {
        width: 100%;
    }

    .org-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #202124;
        margin-bottom: 0.5rem;
    }

    .org-position {
        font-size: 1rem;
        font-weight: 500;
        color: #4285F4;
        margin-bottom: 0.5rem;
    }

    .org-department {
        font-size: 0.875rem;
        color: #5f6368;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .org-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .org-card {
            padding: 1.5rem;
        }

        .org-avatar {
            width: 100px;
            height: 100px;
        }

        .avatar-placeholder {
            font-size: 2.5rem;
        }

        .org-name {
            font-size: 1.125rem;
        }

        .org-position {
            font-size: 0.9375rem;
        }
    }

    @media (max-width: 480px) {
        .org-avatar {
            width: 80px;
            height: 80px;
        }

        .avatar-placeholder {
            font-size: 2rem;
        }
    }
</style>
@endpush
