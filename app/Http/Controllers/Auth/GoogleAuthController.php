<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'You are already logged in as an Administrator.');
        }

        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('warning', 'Google OAuth credentials (GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET) are not configured in .env yet. Please configure them in .env or login with email & password.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }

        // Check if user exists by google_id or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        // Administrator accounts are strictly not allowed to log in via Google OAuth
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Google Sign-In is not allowed for administrator accounts. Please sign in through the Admin Console using your administrator email and password.');
        }

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } else {
            // New registered user via Google is always role 'user'
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Learner',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null,
                'role' => 'user',
            ]);
        }

        Auth::login($user, true);

        if ($user->last_topic_id && $user->lastTopic) {
            return redirect()->route('reader.topic', $user->lastTopic->slug);
        }

        return redirect()->route('reader.home')->with('success', 'Welcome, ' . $user->name . '!');
    }
}
