<div class="stats-grid">
    @foreach($stats as $stat)
        <div class="stat-card">
            <div class="stat-icon">{{ $stat['icon'] }}</div>
            <div class="stat-value">{{ $stat['value'] }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
        </div>
    @endforeach
</div>
