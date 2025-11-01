<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Award a badge to a user
     */
    public function awardBadge(int $userId, int $badgeId): void
    {
        $user = User::findOrFail($userId);

        // Check if user already has this badge
        if (!$user->badges()->where('badge_id', $badgeId)->exists()) {
            $user->badges()->attach($badgeId, [
                'earned_at' => now(),
            ]);
        }
    }

    /**
     * Check and auto-award badges based on user's achievements
     *
     * @return int Number of badges awarded
     */
    public function checkAndAwardBadges(int $userId): int
    {
        $user = User::findOrFail($userId);
        $awardedCount = 0;

        // Get user's current badges
        $currentBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        // Get point breakdown by category
        $pointBreakdown = Point::where('user_id', $userId)
            ->select('category', DB::raw('SUM(value) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Get total assessments count
        $totalAssessments = Point::where('user_id', $userId)->count();

        $totalPoints = $user->total_points;

        // Get all auto-award badges
        $badges = Badge::where('auto_award', true)->get();

        foreach ($badges as $badge) {
            // Skip if user already has this badge
            if (in_array($badge->id, $currentBadgeIds)) {
                continue;
            }

            $shouldAward = false;

            // Check criteria based on type
            switch ($badge->criteria_type) {
                case 'points':
                    // Total points milestone
                    $shouldAward = $totalPoints >= $badge->criteria_value;
                    break;

                case 'assessments':
                    // Total number of assessments received
                    $shouldAward = $totalAssessments >= $badge->criteria_value;
                    break;

                case 'commitment':
                case 'collaboration':
                case 'initiative':
                case 'responsibility':
                    // Category-specific points
                    $categoryPoints = $pointBreakdown[$badge->criteria_type] ?? 0;
                    $shouldAward = $categoryPoints >= $badge->criteria_value;
                    break;
            }

            // Award badge if criteria met
            if ($shouldAward) {
                $this->awardBadge($userId, $badge->id);
                $awardedCount++;
            }
        }

        return $awardedCount;
    }

    /**
     * Get user's badges
     */
    public function getUserBadges(int $userId)
    {
        return User::findOrFail($userId)
            ->badges()
            ->orderBy('earned_at', 'desc')
            ->get();
    }

    /**
     * Get all available badges
     */
    public function getAllBadges()
    {
        return Badge::orderBy('name')->get();
    }

    /**
     * Get badge statistics (how many users have each badge)
     */
    public function getBadgeStats(): array
    {
        $badges = Badge::withCount('users')->get();

        return $badges->map(function ($badge) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'users_count' => $badge->users_count,
            ];
        })->toArray();
    }

    /**
     * Get badges that user is eligible for (without awarding them)
     * Used for dry-run checks
     */
    public function getEligibleBadges(User $user)
    {
        $userId = $user->id;

        // Get point breakdown by category
        $pointBreakdown = Point::where('user_id', $userId)
            ->select('category', DB::raw('SUM(value) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Get total assessments count
        $totalAssessments = Point::where('user_id', $userId)->count();
        $totalPoints = $user->total_points;

        // Get all auto-award badges
        $badges = Badge::where('auto_award', true)->get();

        return $badges->filter(function ($badge) use ($totalPoints, $totalAssessments, $pointBreakdown) {
            switch ($badge->criteria_type) {
                case 'points':
                    return $totalPoints >= $badge->criteria_value;

                case 'assessments':
                    return $totalAssessments >= $badge->criteria_value;

                case 'commitment':
                case 'collaboration':
                case 'initiative':
                case 'responsibility':
                    $categoryPoints = $pointBreakdown[$badge->criteria_type] ?? 0;
                    return $categoryPoints >= $badge->criteria_value;

                default:
                    return false;
            }
        });
    }
}
