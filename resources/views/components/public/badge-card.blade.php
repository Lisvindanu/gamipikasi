@push('styles')
<style>
.badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.badge-card {
    background: white;
    border: 1px solid #e8eaed;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: all 0.2s;
}

.badge-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.badge-icon-large {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.badge-name {
    font-size: 1rem;
    font-weight: 500;
    color: #202124;
    margin-bottom: 0.5rem;
}

.badge-description {
    font-size: 0.875rem;
    color: #5f6368;
    line-height: 1.5;
}
</style>
@endpush

<div class="badge-card">
    <div class="badge-icon-large">{{ $badge->icon }}</div>
    <div class="badge-name">{{ $badge->name }}</div>
    <div class="badge-description">{{ $badge->description }}</div>
</div>
