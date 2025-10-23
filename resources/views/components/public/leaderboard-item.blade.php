@push('styles')
<style>
.leaderboard-grid {
    display: grid;
    gap: 1rem;
}

.leaderboard-item {
    background: white;
    border: 1px solid #e8eaed;
    border-radius: 8px;
    padding: 1.5rem;
    display: grid;
    grid-template-columns: 60px 1fr auto;
    gap: 1.5rem;
    align-items: center;
    transition: all 0.2s;
}

.leaderboard-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.leaderboard-item.rank-1,
.leaderboard-item.rank-2,
.leaderboard-item.rank-3 {
    border-width: 2px;
}

.leaderboard-item.rank-1 {
    border-color: #FBBC04;
    background: linear-gradient(to right, #fffbf0, white);
}

.leaderboard-item.rank-2 {
    border-color: #c0c0c0;
    background: linear-gradient(to right, #f8f8f8, white);
}

.leaderboard-item.rank-3 {
    border-color: #cd7f32;
    background: linear-gradient(to right, #fff5eb, white);
}

.rank-number {
    font-size: 2rem;
    font-weight: 700;
    color: #5f6368;
    text-align: center;
}

.leaderboard-item.rank-1 .rank-number {
    color: #FBBC04;
}

.leaderboard-item.rank-2 .rank-number {
    color: #9e9e9e;
}

.leaderboard-item.rank-3 .rank-number {
    color: #cd7f32;
}

.member-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.member-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--google-blue);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.25rem;
}

.member-details {
    flex: 1;
}

.member-name {
    font-size: 1.125rem;
    font-weight: 500;
    color: #202124;
    margin-bottom: 0.25rem;
}

.member-dept {
    font-size: 0.875rem;
    color: #5f6368;
}

.member-badges {
    display: flex;
    gap: 0.25rem;
    margin-top: 0.5rem;
}

.badge-emoji {
    font-size: 1.125rem;
}

.member-score {
    text-align: right;
}

.score-value {
    font-size: 2rem;
    font-weight: 600;
    color: #202124;
}

.score-label {
    font-size: 0.875rem;
    color: #5f6368;
}

@media (max-width: 768px) {
    .leaderboard-item {
        grid-template-columns: 50px 1fr;
        gap: 1rem;
    }

    .member-score {
        grid-column: 2;
        text-align: left;
        margin-top: 0.5rem;
    }
}
</style>
@endpush

<a href="{{ route('profile.show', $member) }}" style="text-decoration: none; color: inherit; display: block;">
    <div class="leaderboard-item {{ $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : '')) }}">
        <div class="rank-number">
            @if($index === 0) 🥇
            @elseif($index === 1) 🥈
            @elseif($index === 2) 🥉
            @else #{{ $index + 1 }}
            @endif
        </div>
        <div class="member-info">
            <div class="member-avatar" style="@if($member->avatar_path) background-image: url('{{ asset('storage/' . $member->avatar_path) }}'); background-size: cover; background-position: center; @endif">
                @if(!$member->avatar_path)
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                @endif
            </div>
            <div class="member-details">
                <div class="member-name">
                    {{ $member->name }}
                    @if($member->role === 'head')
                        <span style="display: inline-block; padding: 0.125rem 0.5rem; background: linear-gradient(135deg, #4285f4, #34a853); color: white; font-size: 0.625rem; font-weight: 600; border-radius: 12px; text-transform: uppercase; margin-left: 0.5rem; vertical-align: middle;">HEAD</span>
                    @endif
                </div>
                <div class="member-dept">{{ $member->department?->name ?? 'No Department' }}</div>
                @if($member->badges->count() > 0)
                    <div class="member-badges">
                        @foreach($member->badges->take(6) as $badge)
                            <span class="badge-emoji">{{ $badge->icon }}</span>
                        @endforeach
                        @if($member->badges->count() > 6)
                            <span style="font-size: 0.75rem; color: #5f6368;">+{{ $member->badges->count() - 6 }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="member-score">
            <div class="score-value">{{ $member->total_points }}</div>
            <div class="score-label">points</div>
        </div>
    </div>
</a>
