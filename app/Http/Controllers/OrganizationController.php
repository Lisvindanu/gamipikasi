<?php

namespace App\Http\Controllers;

use App\Models\OrganizationPosition;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    // Public view - semua orang bisa lihat
    public function index()
    {
        // Get users with organization positions, ordered by organization_order
        $members = User::whereNotNull('organization_position')
            ->orderBy('organization_order')
            ->get();

        return view('organization.index', compact('members'));
    }

    // Management page - hanya untuk Lead, Co-Lead, dan Head HR
    public function manage()
    {
        $user = auth()->user();

        // Only Lead, Co-Lead, and Head HR can manage organization
        if (!in_array($user->role, ['lead', 'co-lead']) &&
            !($user->role === 'head' && $user->department_id == 1)) {
            abort(403, 'Unauthorized access');
        }

        $members = User::whereNotNull('organization_position')
            ->orderBy('organization_order')
            ->get();

        $users = User::orderBy('name')->get();

        return view('organization.manage', compact('members', 'users'));
    }

    // Store new position
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'position_name' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->update([
            'organization_position' => $validated['position_name'],
            'organization_order' => $validated['order'] ?? 999,
        ]);

        return redirect()->back()->with('success', 'Position added successfully');
    }

    // Update position
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'position_name' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        $user->update([
            'organization_position' => $validated['position_name'],
            'organization_order' => $validated['order'] ?? 999,
        ]);

        return redirect()->back()->with('success', 'Position updated successfully');
    }

    // Delete position
    public function destroy(User $user)
    {
        $user->update([
            'organization_position' => null,
            'organization_order' => 999,
        ]);

        return redirect()->back()->with('success', 'Position removed successfully');
    }
}
