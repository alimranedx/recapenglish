<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    /**
     * Display a list of admins (Super Admin only).
     */
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->latest()->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show form to create a new admin.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created admin (Super Admin only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:admin,super_admin'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.admins.index')->with('success', "New {$validated['role']} account created successfully.");
    }

    /**
     * Delete an admin account.
     */
    public function destroy(int $id)
    {
        $targetUser = User::findOrFail($id);

        if ($targetUser->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own Super Admin account.');
        }

        $targetUser->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin account removed successfully.');
    }
}
