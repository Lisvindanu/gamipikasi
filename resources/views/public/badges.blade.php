@extends('layouts.public')

@section('title', 'Achievement Badges')

@section('content')
    <!-- Hero Section -->
    @include('components.public.hero', [
        'title' => 'Achievement Badges',
        'description' => 'Kumpulkan badge dengan mencapai milestone tertentu. Setiap badge merepresentasikan pencapaian dan dedikasi Anda dalam berkontribusi di GDGoC.'
    ])

    <!-- Main Content -->
    <main class="main-container">
        <!-- Badges Grid -->
        <section class="section">
            <div class="badges-grid">
                @foreach($badges as $badge)
                    @include('components.public.badge-card', ['badge' => $badge])
                @endforeach
            </div>
        </section>

        <!-- How to Earn -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Cara Mendapatkan Badge</h2>
                <p class="section-subtitle">Strategi untuk unlock semua achievements</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                <div style="background: white; border: 1px solid #e8eaed; border-radius: 12px; padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">🎯</div>
                    <h3 style="font-size: 1.25rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Konsisten Aktif</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Badge otomatis di-award ketika Anda mencapai milestone poin tertentu. Tetap aktif dan konsisten
                        dalam setiap aktivitas untuk unlock badge lebih cepat.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e8eaed; border-radius: 12px; padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">📈</div>
                    <h3 style="font-size: 1.25rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Tingkatkan Performa</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Fokus pada kategori penilaian dengan range poin tinggi seperti Initiative (+15 max).
                        Semakin banyak kontribusi ekstra, semakin cepat badge terkumpul.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e8eaed; border-radius: 12px; padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">🤝</div>
                    <h3 style="font-size: 1.25rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Kolaborasi Tim</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Bekerja sama dengan tim dan support sesama anggota. Collaboration yang baik tidak hanya
                        menghasilkan poin, tapi juga memperkuat komunitas.
                    </p>
                </div>

                <div style="background: white; border: 1px solid #e8eaed; border-radius: 12px; padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">⚡</div>
                    <h3 style="font-size: 1.25rem; font-weight: 500; color: #202124; margin-bottom: 1rem;">Avoid Violations</h3>
                    <p style="color: #5f6368; line-height: 1.6;">
                        Jaga komitmen dan hindari violation. Poin minus akan memperlambat progress menuju badge
                        berikutnya. Responsibility is key!
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="section">
            <div style="background: linear-gradient(135deg, var(--google-blue), var(--google-green)); border-radius: 12px; padding: 3rem; text-align: center; color: white;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🏆</div>
                <h3 style="font-size: 2rem; font-weight: 400; margin-bottom: 1rem;">Total {{ $totalBadges ?? count($badges) }} Badges Available</h3>
                <p style="opacity: 0.9; font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
                    Kumpulkan semuanya dan buktikan dedikasi Anda sebagai anggota GDGoC terbaik!
                </p>
            </div>
        </section>
    </main>
@endsection

@push('styles')
<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: repeat(2, 1fr)"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endpush
