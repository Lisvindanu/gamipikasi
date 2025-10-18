@extends('layouts.public')

@section('title', 'Aturan Penilaian')

@section('content')
    <!-- Hero Section -->
    @include('components.public.hero', [
        'title' => 'Aturan Penilaian',
        'description' => 'Sistem penilaian yang objektif dan transparan untuk mengukur kontribusi setiap anggota. Pahami kategori penilaian dan range poin untuk memaksimalkan performa Anda.'
    ])

    <!-- Main Content -->
    <main class="main-container">
        <!-- Rules Grid -->
        <section class="section">
            <div class="rules-grid">
                @include('components.public.rule-card', [
                    'icon' => '💪',
                    'color' => 'blue',
                    'title' => 'Commitment',
                    'description' => 'Konsistensi dan kehadiran dalam aktivitas. Partisipasi rutin dalam meeting, event, dan inisiatif komunitas. Anggota yang aktif dan konsisten akan mendapat nilai tinggi.',
                    'range' => '+1 to +10 points'
                ])

                @include('components.public.rule-card', [
                    'icon' => '🤝',
                    'color' => 'green',
                    'title' => 'Collaboration',
                    'description' => 'Kemampuan bekerja efektif dalam tim. Mendukung sesama anggota dan berkontribusi pada kesuksesan kelompok. Teamwork yang solid adalah kunci.',
                    'range' => '+1 to +10 points'
                ])

                @include('components.public.rule-card', [
                    'icon' => '💡',
                    'color' => 'yellow',
                    'title' => 'Initiative',
                    'description' => 'Proaktif memberikan ide, solusi, dan kontribusi ekstra. Melakukan lebih dari yang diharapkan dan berinisiatif mencari peluang untuk berkembang.',
                    'range' => '+1 to +15 points'
                ])

                @include('components.public.rule-card', [
                    'icon' => '✅',
                    'color' => 'green',
                    'title' => 'Responsibility',
                    'description' => 'Menyelesaikan tugas tepat waktu dan memenuhi ekspektasi. Reliable dan accountable untuk tugas yang diberikan. Tanggung jawab adalah prioritas.',
                    'range' => '+1 to +10 points'
                ])

                @include('components.public.rule-card', [
                    'icon' => '⚠️',
                    'color' => 'red',
                    'title' => 'Violation',
                    'description' => 'Ketidakhadiran, inaktivitas, atau pelanggaran aturan. Poin dapat dikurangi untuk komitmen yang terlewat. Hindari violation untuk menjaga skor Anda.',
                    'range' => '-1 to -10 points'
                ])

                @include('components.public.rule-card', [
                    'icon' => '🎯',
                    'color' => 'blue',
                    'title' => 'No Limit',
                    'description' => 'Tidak ada batas maksimal poin! Semakin aktif dan konsisten Anda, semakin tinggi skor yang bisa diraih. Sky is the limit!',
                    'range' => '∞ Unlimited Growth'
                ])
            </div>
        </section>

        <!-- Additional Info -->
        <section class="section">
            <div style="background: #f8f9fa; border-radius: 12px; padding: 2.5rem; border-left: 4px solid var(--google-blue);">
                <h3 style="font-size: 1.5rem; font-weight: 500; color: #202124; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="info" style="width: 24px; height: 24px; color: var(--google-blue);"></i>
                    Informasi Penting
                </h3>
                <ul style="color: #5f6368; line-height: 1.8; margin-left: 1.5rem;">
                    <li>Penilaian dilakukan oleh tim HR setiap akhir bulan</li>
                    <li>Setiap pemberian poin akan disertai dengan catatan/alasan</li>
                    <li>Anggota dapat melihat history poin mereka secara real-time</li>
                    <li>Total poin akumulatif akan direview di akhir periode (1 tahun)</li>
                    <li>Transparansi penuh - semua anggota dapat tracking progress mereka</li>
                </ul>
            </div>
        </section>
    </main>
@endsection
