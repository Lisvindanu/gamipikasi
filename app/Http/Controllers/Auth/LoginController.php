<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect to appropriate dashboard based on user role
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();

        return match ($user->role) {
            'lead', 'co-lead' => redirect()->route('lead.dashboard'),
            'secretary' => redirect()->route('member.dashboard'), // Secretary is admin role, use member dashboard
            'head' => match ($user->department_id) {
                5 => redirect()->route('hr.dashboard'), // HR Head goes to HR dashboard
                default => redirect()->route('head.dashboard'), // Other heads go to head dashboard
            },
            'member' => match ($user->department_id) {
                5 => redirect()->route('hr.dashboard'), // HR members go to HR dashboard
                default => redirect()->route('member.dashboard'), // Other members go to member dashboard
            },
            default => redirect()->route('member.dashboard'),
        };
    }
}
