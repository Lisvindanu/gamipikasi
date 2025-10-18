@push('styles')
<style>
.rules-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 3rem;
}

.rule-card {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    border: 1px solid #e8eaed;
}

.rule-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1.5rem;
}

.rule-icon.blue {
    background: rgba(66, 133, 244, 0.1);
}

.rule-icon.green {
    background: rgba(52, 168, 83, 0.1);
}

.rule-icon.yellow {
    background: rgba(251, 188, 4, 0.1);
}

.rule-icon.red {
    background: rgba(234, 67, 53, 0.1);
}

.rule-title {
    font-size: 1.25rem;
    font-weight: 500;
    color: #202124;
    margin-bottom: 0.75rem;
}

.rule-description {
    font-size: 0.95rem;
    color: #5f6368;
    line-height: 1.6;
}

.rule-range {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    font-weight: 500;
    color: #202124;
    text-align: center;
}

@media (max-width: 1024px) {
    .rules-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

<div class="rule-card">
    <div class="rule-icon {{ $color }}">{{ $icon }}</div>
    <h3 class="rule-title">{{ $title }}</h3>
    <p class="rule-description">{{ $description }}</p>
    <div class="rule-range">{{ $range }}</div>
</div>
