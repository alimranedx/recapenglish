<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', 'user')->with([
            'lastTopic.chapter',
            'topicReads',
            'skillTestAttempts' => fn($q) => $q->where('status', 'completed')->latest(),
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest('last_read_at')->paginate(20)->withQueryString();
        $totalTopics = Topic::where('is_published', true)->count();

        return view('admin.students.index', compact('students', 'search', 'totalTopics'));
    }
}
