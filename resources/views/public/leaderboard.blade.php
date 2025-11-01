@extends('layouts.public')

@section('title', 'GDGoC Papan Peringkat')

@section('content')
    <!-- Hero Section -->
    @include('components.public.hero')

    <!-- Main Content -->
    <main class="main-container">
        <!-- Stats Section -->
        @include('components.public.stats-grid', [
            'stats' => [
                ['icon' => '👥', 'value' => $stats['total_active_members'], 'label' => 'Anggota Aktif'],
                ['icon' => '🏢', 'value' => $stats['total_departments'], 'label' => 'Departemen'],
                ['icon' => '🎖️', 'value' => $stats['total_badges_awarded'], 'label' => 'Lencana Diraih'],
                ['icon' => '⭐', 'value' => $stats['highest_score'], 'label' => 'Skor Tertinggi'],
            ]
        ])

        <!-- Papan Peringkat Head Section -->
        <section id="leaderboard-heads" class="section">
            <div class="section-header">
                <h2 class="section-title">🏅 Peringkat Head Department</h2>
                <p class="section-subtitle">Para kepala departemen dengan pencapaian terbaik</p>
            </div>

            <div class="leaderboard-grid">
                @forelse($topHeads as $index => $member)
                    @include('components.public.leaderboard-item', ['member' => $member, 'index' => $index])
                @empty
                    <div style="text-align: center; padding: 4rem; color: #5f6368;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🏆</div>
                        <div style="font-size: 1.125rem;">Belum ada head. Jadilah yang pertama meraih poin!</div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Papan Peringkat Member/Staff Section -->
        <section id="leaderboard-members" class="section">
            <div class="section-header">
                <h2 class="section-title">⭐ Peringkat Staff & Member</h2>
                <p class="section-subtitle">Staff dan member yang menonjol dan memimpin periode ini</p>
            </div>

            <div class="leaderboard-grid">
                @forelse($topMembers as $index => $member)
                    @include('components.public.leaderboard-item', ['member' => $member, 'index' => $index])
                @empty
                    <div style="text-align: center; padding: 4rem; color: #5f6368;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🏆</div>
                        <div style="font-size: 1.125rem;">Belum ada member. Jadilah yang pertama meraih poin!</div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Badges Section -->
        <section id="badges" class="section">
            <div class="section-header">
                <h2 class="section-title">Lencana Pencapaian</h2>
                <p class="section-subtitle">Buka lencana ini dengan mencapai target</p>
            </div>

            <div class="badges-grid">
                @foreach($badges as $badge)
                    @include('components.public.badge-card', ['badge' => $badge])
                @endforeach
            </div>
        </section>
    </main>

    <!-- Rules Section -->
    <section id="rules" class="section-gray">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Kategori Penilaian</h2>
                <p class="section-subtitle">Cara kami mengevaluasi dan memberikan reward kepada anggota</p>
            </div>

            <div class="rules-grid">
                @include('components.public.rule-card', [
                    'icon' => '💪',
                    'color' => 'blue',
                    'title' => 'Commitment',
                    'description' => 'Konsistensi dan kehadiran dalam aktivitas. Partisipasi rutin dalam rapat, acara, dan inisiatif komunitas.',
                    'range' => '+1 hingga +10 poin'
                ])

                @include('components.public.rule-card', [
                    'icon' => '🤝',
                    'color' => 'green',
                    'title' => 'Collaboration',
                    'description' => 'Kemampuan bekerja efektif dalam tim. Mendukung sesama anggota dan berkontribusi pada kesuksesan kelompok.',
                    'range' => '+1 hingga +10 poin'
                ])

                @include('components.public.rule-card', [
                    'icon' => '💡',
                    'color' => 'yellow',
                    'title' => 'Initiative',
                    'description' => 'Proaktif memberikan ide, solusi, dan kontribusi ekstra. Melampaui ekspektasi.',
                    'range' => '+1 hingga +15 poin'
                ])

                @include('components.public.rule-card', [
                    'icon' => '✅',
                    'color' => 'green',
                    'title' => 'Responsibility',
                    'description' => 'Menyelesaikan tugas tepat waktu dan memenuhi ekspektasi. Dapat diandalkan dan bertanggung jawab atas tugas yang diberikan.',
                    'range' => '+1 hingga +10 poin'
                ])

                @include('components.public.rule-card', [
                    'icon' => '⚠️',
                    'color' => 'red',
                    'title' => 'Violation',
                    'description' => 'Ketidakhadiran, tidak aktif, atau pelanggaran aturan. Poin dapat dikurangi karena tidak memenuhi komitmen.',
                    'range' => '-1 hingga -10 poin'
                ])

                @include('components.public.rule-card', [
                    'icon' => '🎯',
                    'color' => 'blue',
                    'title' => 'Tanpa Batas',
                    'description' => 'Tidak ada batas maksimum poin! Semakin aktif dan konsisten, semakin tinggi skor yang bisa dicapai.',
                    'range' => '∞ Pertumbuhan Tanpa Batas'
                ])
            </div>
        </div>
    </section>
@endsection
