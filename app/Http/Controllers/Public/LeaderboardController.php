<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Badge;

class LeaderboardController extends Controller
{
    /**
     * Show public home page
     */
    public function home()
    {
        // Stats
        $stats = [
            'total_active_members' => User::where('role', 'member')
                ->where('total_points', '>', 0)
                ->count(),
            'total_departments' => Department::count(),
            'total_badges_awarded' => User::withCount('badges')
                ->get()
                ->sum('badges_count'),
            'highest_score' => User::where('role', 'member')->max('total_points') ?? 0,
        ];

        return view('public.home', compact('stats'));
    }

    /**
     * Show public leaderboard
     * Only shows positive points, hides violations/negative points
     */
    public function leaderboard()
    {
        // Get top performers (only members with positive points)
        $topPerformers = User::with(['department', 'badges'])
            ->where('role', 'member')
            ->where('total_points', '>', 0)
            ->orderBy('total_points', 'desc')
            ->limit(20)
            ->get();

        // Get department rankings (based on average positive points)
        $departmentRankings = Department::withAvg(['members' => function($query) {
            $query->where('total_points', '>', 0);
        }], 'total_points')
        ->withCount(['members' => function($query) {
            $query->where('total_points', '>', 0);
        }])
        ->having('members_count', '>', 0)
        ->orderBy('members_avg_total_points', 'desc')
        ->get();

        // Get recent badge earners
        $recentBadges = User::with(['badges' => function($query) {
            $query->orderBy('user_badges.earned_at', 'desc')
                  ->limit(5);
        }])
        ->whereHas('badges')
        ->where('total_points', '>', 0)
        ->limit(10)
        ->get();

        // Get all badges for display
        $badges = Badge::all();

        // Stats
        $stats = [
            'total_active_members' => User::where('role', 'member')
                ->where('total_points', '>', 0)
                ->count(),
            'total_departments' => Department::count(),
            'total_badges_awarded' => User::withCount('badges')
                ->get()
                ->sum('badges_count'),
            'highest_score' => User::where('role', 'member')->max('total_points') ?? 0,
        ];

        return view('public.leaderboard', compact(
            'topPerformers',
            'departmentRankings',
            'recentBadges',
            'badges',
            'stats'
        ));
    }

    /**
     * Show rules page
     */
    public function rules()
    {
        return view('public.rules');
    }

    /**
     * Show badges page
     */
    public function badges()
    {
        $badges = Badge::all();
        $totalBadges = $badges->count();

        return view('public.badges', compact('badges', 'totalBadges'));
    }
}
