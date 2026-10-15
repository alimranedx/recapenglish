<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\Part;
use App\Models\SkillTestAttempt;
use App\Models\Topic;
use App\Models\TopicRead;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. High-level metric counts
        $totalStudents = User::where('role', 'user')->count();
        $activeStudents7d = User::where('role', 'user')
            ->where('last_read_at', '>=', now()->subDays(7))
            ->count();

        $completedSkillTestsCount = SkillTestAttempt::where('status', 'completed')->count();
        $avgScore = SkillTestAttempt::where('status', 'completed')->avg('score') ?? 0;
        $avgPercentage = SkillTestAttempt::where('status', 'completed')->avg('percentage') ?? 0;
        $highPerformersCount = SkillTestAttempt::where('status', 'completed')->where('percentage', '>=', 80)->count();
        $avgDurationSeconds = SkillTestAttempt::where('status', 'completed')
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds') ?? 0;

        $stats = [
            'total_parts' => Part::count(),
            'total_chapters' => Chapter::count(),
            'total_topics' => Topic::count(),
            'published_topics' => Topic::where('is_published', true)->count(),
            'total_exercises' => Exercise::count(),
            'total_students' => $totalStudents,
            'active_students_7d' => $activeStudents7d,
            'completed_reads' => TopicRead::where('is_completed', true)->count(),
            'total_skill_tests' => $completedSkillTestsCount,
            'avg_test_score' => round($avgScore, 1),
            'avg_test_percentage' => round($avgPercentage, 1),
            'high_performers_count' => $highPerformersCount,
            'avg_duration_minutes' => round($avgDurationSeconds / 60, 1),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        // 2. Grammar Topic Difficulty & Diagnostic Aggregation from Skill Tests
        $completedAttempts = SkillTestAttempt::where('status', 'completed')
            ->select('id', 'topic_breakdown')
            ->get();

        $topicStatsAgg = [];
        foreach ($completedAttempts as $attempt) {
            if (is_array($attempt->topic_breakdown)) {
                foreach ($attempt->topic_breakdown as $item) {
                    $tId = $item['topic_id'] ?? null;
                    if (!$tId) {
                        continue;
                    }
                    if (!isset($topicStatsAgg[$tId])) {
                        $topicStatsAgg[$tId] = [
                            'total_asked' => 0,
                            'correct' => 0,
                            'wrong' => 0,
                            'unanswered' => 0,
                        ];
                    }
                    $topicStatsAgg[$tId]['total_asked'] += (int) ($item['total'] ?? 0);
                    $topicStatsAgg[$tId]['correct'] += (int) ($item['correct'] ?? 0);
                    $topicStatsAgg[$tId]['wrong'] += (int) ($item['wrong'] ?? 0);
                    $topicStatsAgg[$tId]['unanswered'] += (int) ($item['unanswered'] ?? 0);
                }
            }
        }

        $allTopics = Topic::with('chapter.part')
            ->withCount('exercises')
            ->get()
            ->map(function ($topic) use ($topicStatsAgg) {
                $agg = $topicStatsAgg[$topic->id] ?? null;
                $totalAsked = $agg['total_asked'] ?? 0;
                $correct = $agg['correct'] ?? 0;
                $wrong = $agg['wrong'] ?? 0;
                $accuracy = $totalAsked > 0 ? round(($correct / $totalAsked) * 100, 1) : null;

                $difficultyLabel = 'No Test Data';
                $difficultyColor = 'slate';
                if ($accuracy !== null) {
                    if ($accuracy < 55) {
                        $difficultyLabel = 'Challenging (Low Accuracy)';
                        $difficultyColor = 'rose';
                    } elseif ($accuracy < 75) {
                        $difficultyLabel = 'Moderate';
                        $difficultyColor = 'amber';
                    } else {
                        $difficultyLabel = 'Strong Mastery';
                        $difficultyColor = 'emerald';
                    }
                }

                return (object) [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'slug' => $topic->slug,
                    'chapter' => $topic->chapter->title ?? '',
                    'part' => $topic->chapter->part->title ?? '',
                    'is_published' => $topic->is_published,
                    'exercises_count' => $topic->exercises_count,
                    'total_asked' => $totalAsked,
                    'correct' => $correct,
                    'wrong' => $wrong,
                    'accuracy' => $accuracy,
                    'difficulty_label' => $difficultyLabel,
                    'difficulty_color' => $difficultyColor,
                ];
            });

        // Diagnostic rankings
        $topicsWithAttempts = $allTopics->filter(fn($t) => $t->accuracy !== null);
        $hardestTopics = $topicsWithAttempts->sortBy('accuracy')->take(5);
        $strongestTopics = $topicsWithAttempts->sortByDesc('accuracy')->take(5);
        $questionPoolAudit = $allTopics->sortBy('exercises_count')->take(5);

        // 3. Recent Activity Streams
        $recentStudents = User::where('role', 'user')
            ->with(['lastTopic.chapter', 'skillTestAttempts' => fn($q) => $q->where('status', 'completed')])
            ->withCount(['topicReads as completed_reads_count' => fn($q) => $q->where('is_completed', true)])
            ->latest()
            ->take(6)
            ->get();

        $recentSkillTests = SkillTestAttempt::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'allTopics',
            'hardestTopics',
            'strongestTopics',
            'questionPoolAudit',
            'recentStudents',
            'recentSkillTests'
        ));
    }
}
