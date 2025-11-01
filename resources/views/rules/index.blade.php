@extends('layouts.app')

@section('title', 'Peraturan Umum')

@push('styles')
<style>
    .rules-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .rules-header {
        background: linear-gradient(135deg, var(--google-blue), var(--google-green));
        border-radius: 20px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 3rem;
        text-align: center;
    }

    .rules-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .rules-header p {
        font-size: 1.125rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .rule-card {
        background: white;
        border: 1px solid #e8eaed;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .rule-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .rule-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .rule-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .rule-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .rule-number {
        color: var(--google-blue);
        font-weight: 700;
    }

    .rule-content {
        margin-left: 3rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    .rule-content p {
        margin-bottom: 0.5rem;
    }

    .rule-content strong {
        color: var(--text-primary);
        font-weight: 600;
    }

    .rules-footer {
        background: #f8f9fa;
        border-left: 4px solid var(--google-blue);
        border-radius: 12px;
        padding: 2rem;
        margin-top: 3rem;
        text-align: center;
    }

    .rules-footer-quote {
        font-size: 1.125rem;
        font-style: italic;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }

    .rules-footer-source {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 600;
    }

    .info-box {
        background: #e8f4fd;
        border-left: 4px solid var(--google-blue);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 3rem;
        margin-bottom: 2rem;
    }

    .info-box-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .info-box-content {
        color: var(--text-secondary);
        line-height: 1.7;
    }

    @media (max-width: 768px) {
        .rules-header h1 {
            font-size: 1.75rem;
        }

        .rules-header p {
            font-size: 1rem;
        }

        .rule-card {
            padding: 1.5rem;
        }

        .rule-content {
            margin-left: 0;
            margin-top: 1rem;
        }

        .rule-icon {
            font-size: 1.5rem;
        }

        .rule-title {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="rules-container">
    <!-- Header -->
    <div class="rules-header">
        <h1>🧭 8 Aturan Utama GDGoC Unpas Core Team</h1>
        <p>Pedoman dan standar profesionalitas untuk seluruh anggota Core Team periode aktif</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-box-title">
            <i data-lucide="info" style="width: 20px; height: 20px;"></i>
            Mengapa Aturan Ini Penting?
        </div>
        <div class="info-box-content">
            Aturan ini dibuat untuk memastikan setiap anggota Core Team dapat berkontribusi secara maksimal,
            menjaga profesionalitas, dan menciptakan lingkungan kerja yang sehat dan produktif.
            Kepatuhan terhadap aturan ini akan berdampak pada penilaian poin dan evaluasi kinerja.
        </div>
    </div>

    <!-- Rule 1 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">📅</span>
            <h2 class="rule-title"><span class="rule-number">1.</span> Rapat Mingguan Wajib</h2>
        </div>
        <div class="rule-content">
            <p>Setiap departemen wajib mengadakan <strong>rapat mingguan minimal satu kali setiap minggu</strong>.</p>
            <p>Tujuannya untuk menyelaraskan progres, kendala, dan rencana kegiatan yang sedang berjalan. Keterlambatan atau ketidakhadiran tanpa konfirmasi akan mempengaruhi penilaian poin.</p>
        </div>
    </div>

    <!-- Rule 2 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">💬</span>
            <h2 class="rule-title"><span class="rule-number">2.</span> Komunikasi Terbuka dan Aktif</h2>
        </div>
        <div class="rule-content">
            <p>Setiap anggota diharapkan <strong>terlibat aktif dalam komunikasi internal</strong>, baik di grup, Notion, maupun platform resmi GDGoC.</p>
            <p>Tidak ada progres tanpa komunikasi — menyampaikan update kecil tetap lebih baik daripada diam.</p>
        </div>
    </div>

    <!-- Rule 3 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">⏰</span>
            <h2 class="rule-title"><span class="rule-number">3.</span> Tanggung Jawab dan Ketepatan Waktu</h2>
        </div>
        <div class="rule-content">
            <p>Tugas yang diberikan harus diselesaikan sesuai tenggat waktu.</p>
            <p>Kedisiplinan bukan sekadar formalitas, tapi bentuk <strong>profesionalisme dan penghargaan terhadap waktu orang lain</strong>.</p>
        </div>
    </div>

    <!-- Rule 4 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">💡</span>
            <h2 class="rule-title"><span class="rule-number">4.</span> Inisiatif dan Kontribusi</h2>
        </div>
        <div class="rule-content">
            <p>Core Team didorong untuk <strong>proaktif memberi ide, solusi, atau usulan kegiatan baru</strong>.</p>
            <p>Setiap ide yang membawa dampak positif akan mendapat apresiasi dalam bentuk poin tambahan dan pengakuan tim.</p>
        </div>
    </div>

    <!-- Rule 5 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">🤝</span>
            <h2 class="rule-title"><span class="rule-number">5.</span> Kolaborasi Antar Departemen</h2>
        </div>
        <div class="rule-content">
            <p>Setiap departemen wajib berkolaborasi bila terdapat program lintas bidang.</p>
            <p><strong>GDGoC Unpas tidak berjalan secara terpisah</strong>; keberhasilan satu departemen adalah keberhasilan bersama.</p>
        </div>
    </div>

    <!-- Rule 6 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">🚫</span>
            <h2 class="rule-title"><span class="rule-number">6.</span> Etika dan Kepatuhan</h2>
        </div>
        <div class="rule-content">
            <p>Setiap anggota wajib menjaga sikap profesional baik di lingkungan internal maupun eksternal.</p>
            <p>Pelanggaran seperti ketidakhadiran berulang, konflik personal, atau penyalahgunaan wewenang akan berdampak pada <strong>pengurangan poin dan evaluasi keanggotaan</strong>.</p>
        </div>
    </div>

    <!-- Rule 7 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">🌱</span>
            <h2 class="rule-title"><span class="rule-number">7.</span> Pertumbuhan dan Evaluasi Berkala</h2>
        </div>
        <div class="rule-content">
            <p>Selama satu periode (1 tahun), performa anggota akan <strong>dievaluasi secara berkala oleh tim HR dan Lead</strong>.</p>
            <p>Evaluasi mencakup kedisiplinan, kontribusi, dan kerja sama tim. Anggota dengan perkembangan konsisten akan mendapatkan penghargaan di akhir periode.</p>
        </div>
    </div>

    <!-- Rule 8 -->
    <div class="rule-card">
        <div class="rule-header">
            <span class="rule-icon">❤️</span>
            <h2 class="rule-title"><span class="rule-number">8.</span> Jaga Profesionalitas — Jangan Bawa Persoalan Pribadi</h2>
        </div>
        <div class="rule-content">
            <p>Persoalan pribadi tidak boleh dibawa ke dalam lingkungan komunitas.</p>
            <p>Setiap anggota diharapkan dapat <strong>memisahkan urusan personal dengan tanggung jawab organisasi</strong>, demi menjaga suasana kerja yang sehat dan saling menghargai.</p>
        </div>
    </div>

    <!-- Footer Quote -->
    <div class="rules-footer">
        <div class="rules-footer-quote">
            "Kedisiplinan menjaga ritme, kolaborasi menjaga arah, dan profesionalitas menjaga keutuhan."
        </div>
        <div class="rules-footer-source">
            — GDGoC Unpas Core Team 2025
        </div>
    </div>
</div>
@endsection
