<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Point;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show member profile page
     */
    public function show(User $user)
    {
        // Load relationships
        $user->load([
            'department',
            'badges',
            'points' => function($query) {
                $query->latest()->limit(20);
            }
        ]);

        // Get statistics
        $stats = [
            'total_points' => $user->total_points,
            'rank' => User::where('total_points', '>', $user->total_points)->count() + 1,
            'total_assessments' => Point::where('user_id', $user->id)->count(),
            'badges_count' => $user->badges->count(),
            'positive_points' => Point::where('user_id', $user->id)
                ->where('value', '>', 0)
                ->sum('value'),
            'negative_points' => Point::where('user_id', $user->id)
                ->where('value', '<', 0)
                ->sum('value'),
        ];

        // Points breakdown by category
        $pointsBreakdown = Point::where('user_id', $user->id)
            ->selectRaw('category, SUM(value) as total, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->keyBy('category');

        // Recent activities (points history)
        $recentActivities = Point::where('user_id', $user->id)
            ->with('assessor:id,name')
            ->latest()
            ->limit(10)
            ->get();

        // All badges (to show locked badges)
        $allBadges = Badge::all();
        $earnedBadgeIds = $user->badges->pluck('id')->toArray();

        return view('profile.show', compact(
            'user',
            'stats',
            'pointsBreakdown',
            'recentActivities',
            'allBadges',
            'earnedBadgeIds'
        ));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Update avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old avatar if exists
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar_path' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
