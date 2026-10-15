<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\SkillTestAttempt;
use App\Models\Topic;
use App\Models\TopicRead;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_parts' => Part::count(),
            'total_chapters' => Chapter::count(),
            'total_topics' => Topic::count(),
            'total_exercises' => Exercise::count(),
            'total_students' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'completed_reads' => TopicRead::where('is_completed', true)->count(),
            'total_skill_tests' => SkillTestAttempt::where('status', 'completed')->count(),
        ];

        $recentStudents = User::where('role', 'user')
            ->with('lastTopic.chapter')
            ->latest()
            ->take(6)
            ->get();

        $recentTopics = Topic::with('chapter.part')
            ->latest()
            ->take(6)
            ->get();

        $recentSkillTests = SkillTestAttempt::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentTopics', 'recentSkillTests'));
    }
}
