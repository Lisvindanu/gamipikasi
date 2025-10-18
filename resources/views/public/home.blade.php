@extends('layouts.public')

@section('title', 'GDGoC Gamification System')

@section('content')
    <!-- Hero Section -->
    @include('components.public.hero', [
        'title' => 'GDGoC Performance Tracking System',
        'description' => 'Sistem gamifikasi untuk mengukur dan menghargai kontribusi anggota Core Team Google Developer Groups on Campus Universitas Pasundan. Raih poin, kumpulkan badge, dan tunjukkan dedikasi terbaikmu!'
    ])

    <!-- Main Content -->
    <main class="main-container">
        <!-- About Section -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Tentang Sistem Gamifikasi</h2>
                <p class="section-subtitle">Membangun budaya apresiasi dan kompetisi sehat</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                <div style="background: white; border: 1px solid #e8eaed; border-radius: 8px; padding: 2rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🎯</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Tujuan</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Sistem ini dirancang untuk mengukur dan menghargai kontribusi setiap anggota Core Team GDGoC.
                        Dengan penilaian yang objektif dan transparan, kami mendorong setiap anggota untuk memberikan
                        yang terbaik dalam setiap aktivitas organisasi.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e8eaed; border-radius: 8px; padding: 2rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📊</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Periode Penilaian</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Penilaian dilakukan selama 1 tahun (1 periode kepengurusan). Evaluasi bulanan dilakukan
                        untuk input skor, dan di akhir periode akan ada rekap total poin untuk menentukan reward
                        dan apresiasi kepada anggota berprestasi.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e8eaed; border-radius: 8px; padding: 2rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🏆</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Reward System</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Anggota dengan total poin tertinggi di akhir periode akan mendapatkan reward dan recognition.
                        Tidak ada batas maksimal poin - semakin aktif dan konsisten, semakin tinggi skor yang bisa diraih!
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e8eaed; border-radius: 8px; padding: 2rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🤝</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Transparansi</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Setiap anggota dapat melihat perkembangan poinnya secara real-time. Leaderboard publik
                        menampilkan top performers untuk memotivasi kompetisi sehat dan saling mendukung antar anggota.
                    </p>
                </div>
            </div>
        </section>

        <!-- Quick Links -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Jelajahi Sistem</h2>
                <p class="section-subtitle">Pelajari lebih lanjut tentang penilaian dan pencapaian</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
                <a href="{{ route('public.leaderboard') }}" style="text-decoration: none; display: block; background: linear-gradient(135deg, #4285F4, #34A853); border-radius: 12px; padding: 2.5rem 2rem; text-align: center; color: white; transition: all 0.3s;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🏅</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0.75rem;">Leaderboard</h3>
                    <p style="opacity: 0.9; font-size: 0.95rem;">Lihat ranking anggota terbaik periode ini</p>
                </a>

                <a href="{{ route('public.rules') }}" style="text-decoration: none; display: block; background: linear-gradient(135deg, #EA4335, #FBBC04); border-radius: 12px; padding: 2.5rem 2rem; text-align: center; color: white; transition: all 0.3s;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0.75rem;">Aturan Penilaian</h3>
                    <p style="opacity: 0.9; font-size: 0.95rem;">Pelajari kategori dan range poin</p>
                </a>

                <a href="{{ route('public.badges') }}" style="text-decoration: none; display: block; background: linear-gradient(135deg, #FBBC04, #34A853); border-radius: 12px; padding: 2.5rem 2rem; text-align: center; color: white; transition: all 0.3s;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎖️</div>
                    <h3 style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0.75rem;">Badge Collection</h3>
                    <p style="opacity: 0.9; font-size: 0.95rem;">Lihat semua pencapaian yang bisa diraih</p>
                </a>
            </div>
        </section>

        <!-- Stats Preview -->
        @include('components.public.stats-grid', [
            'stats' => [
                ['icon' => '👥', 'value' => $stats['total_active_members'], 'label' => 'Active Members'],
                ['icon' => '🏢', 'value' => $stats['total_departments'], 'label' => 'Departments'],
                ['icon' => '🎖️', 'value' => $stats['total_badges_awarded'], 'label' => 'Badges Earned'],
                ['icon' => '⭐', 'value' => $stats['highest_score'], 'label' => 'Highest Score'],
            ]
        ])
    </main>
@endsection

@push('styles')
<style>
a[href*="leaderboard"]:hover,
a[href*="rules"]:hover,
a[href*="badges"]:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    div[style*="grid-template-columns: repeat(2, 1fr)"],
    div[style*="grid-template-columns: repeat(3, 1fr)"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endpush
