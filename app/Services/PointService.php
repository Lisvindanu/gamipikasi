<?php

namespace App\Services;

use App\Models\Point;
use App\Models\User;
use App\PointCategory;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * Add points to a user
     */
    public function addPoint(int $userId, string $category, int $value, int $givenBy, ?string $note = null): Point
    {
        return DB::transaction(function () use ($userId, $category, $value, $givenBy, $note) {
            // Validate point value against category range
            $categoryEnum = PointCategory::from($category);
            $range = $categoryEnum->getPointRange();

            if ($value < $range['min'] || $value > $range['max']) {
                throw new \InvalidArgumentException(
                    "Point value must be between {$range['min']} and {$range['max']} for category {$category}"
                );
            }

            // Create point record
            $point = Point::create([
                'user_id' => $userId,
                'category' => $category,
                'value' => $value,
                'note' => $note,
                'given_by' => $givenBy,
            ]);

            // Update user's total points
            $user = User::findOrFail($userId);
            $user->increment('total_points', $value);

            return $point;
        });
    }

    /**
     * Get point history for a user
     */
    public function getUserPointHistory(int $userId, ?string $category = null)
    {
        $query = Point::where('user_id', $userId)
            ->with('assessor:id,name')
            ->orderBy('created_at', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        return $query->get();
    }

    /**
     * Get point breakdown by category for a user
     */
    public function getUserPointBreakdown(int $userId): array
    {
        $points = Point::where('user_id', $userId)
            ->select('category', DB::raw('SUM(value) as total'))
            ->groupBy('category')
            ->get();

        $breakdown = [
            'commitment' => 0,
            'collaboration' => 0,
            'initiative' => 0,
            'responsibility' => 0,
            'violation' => 0,
        ];

        foreach ($points as $point) {
            $breakdown[$point->category] = $point->total;
        }

        return $breakdown;
    }

    /**
     * Get leaderboard
     */
    public function getLeaderboard(?int $departmentId = null, int $limit = 10)
    {
        $query = User::with('department:id,name')
            ->select('id', 'name', 'email', 'role', 'department_id', 'total_points')
            ->orderBy('total_points', 'desc');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get department statistics
     */
    public function getDepartmentStats(int $departmentId): array
    {
        $users = User::where('department_id', $departmentId)->get();

        return [
            'total_members' => $users->count(),
            'total_points' => $users->sum('total_points'),
            'average_points' => $users->avg('total_points'),
            'top_member' => $users->sortByDesc('total_points')->first(),
        ];
    }

    /**
     * Recalculate user's total points (for maintenance/correction)
     */
    public function recalculateUserPoints(int $userId): void
    {
        $totalPoints = Point::where('user_id', $userId)->sum('value');

        $user = User::findOrFail($userId);
        $user->update(['total_points' => $totalPoints]);
    }

    /**
     * Award points for task completion
     */
    public function awardTaskCompletionPoints(int $userId, int $pointValue, int $givenBy, string $taskTitle): Point
    {
        return DB::transaction(function () use ($userId, $pointValue, $givenBy, $taskTitle) {
            // Create point record
            $point = Point::create([
                'user_id' => $userId,
                'category' => 'responsibility',
                'value' => $pointValue,
                'note' => "Completed task: {$taskTitle}",
                'given_by' => $givenBy,
            ]);

            // Update user's total points
            $user = User::findOrFail($userId);
            $user->increment('total_points', $pointValue);

            return $point;
        });
    }
}
