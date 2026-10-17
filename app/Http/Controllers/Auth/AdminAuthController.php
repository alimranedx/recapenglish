<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Display the dedicated admin login view.
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle an admin authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // If currently logged in as a learner, log out before attempting admin sign-in
        if (Auth::check() && !Auth::user()->isAdmin()) {
            Auth::logout();
        }

        if (Auth::attempt($credentials, $remember)) {
            /** @var User $user */
            $user = Auth::user();

            // Strictly verify that the authenticated account has admin privileges
            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access Denied: This administrative console is restricted to Super Admins and Academic Editors. Regular learner accounts cannot access the admin panel.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to RecapEnglish Admin Console, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided administrator credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the administrator out of the console.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been successfully logged out of the Admin Console.');
    }
}
