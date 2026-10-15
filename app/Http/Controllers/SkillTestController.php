<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\SkillTestAttempt;
use App\Models\SkillTestConfig;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SkillTestController extends Controller
{
    /**
     * Skill Test Landing / Info Page.
     */
    public function index(Request $request)
    {
        $config = SkillTestConfig::getActive();

        $query = Topic::where('is_published', true)
            ->has('exercises')
            ->withCount('exercises')
            ->orderBy('order');

        if ($config->isFixedTopicMode()) {
            $query->whereIn('id', $config->fixed_topic_ids);
        }

        $topics = $query->get();

        $user = Auth::user();
        $recentAttempts = collect();

        if ($user) {
            $recentAttempts = SkillTestAttempt::where('user_id', $user->id)
                ->where('status', 'completed')
                ->latest()
                ->take(10)
                ->get();
        } else {
            $sessionId = $request->session()->getId();
            if ($sessionId) {
                $recentAttempts = SkillTestAttempt::where('session_id', $sessionId)
                    ->where('status', 'completed')
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }

        $totalQuestionsInPool = Exercise::count();
        $totalTopicsCount = $topics->count();

        return view('skill-test.index', compact(
            'topics',
            'recentAttempts',
            'totalQuestionsInPool',
            'totalTopicsCount',
            'user',
            'config'
        ));
    }

    /**
     * Start a new Skill Test attempt with randomized questions based on active exam settings.
     */
    public function start(Request $request)
    {
        $config = SkillTestConfig::getActive();
        $questionIds = SkillTestAttempt::generateRandomSkillTestQuestionIds($config->total_questions);

        if (empty($questionIds)) {
            return redirect()->route('skill-test.index')
                ->with('error', 'No practice questions are currently available to generate a skill test.');
        }

        $user = Auth::user();

        $attempt = SkillTestAttempt::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $user ? $user->id : null,
            'session_id' => $request->session()->getId(),
            'question_ids' => $questionIds,
            'total_questions' => count($questionIds),
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('skill-test.take', $attempt->uuid);
    }

    /**
     * Present the interactive skill test.
     */
    public function take(Request $request, string $uuid)
    {
        $attempt = SkillTestAttempt::where('uuid', $uuid)->firstOrFail();

        // If already submitted, jump directly to the score and analysis
        if ($attempt->status === 'completed') {
            return redirect()->route('skill-test.result', $attempt->uuid);
        }

        $config = SkillTestConfig::getActive();
        $exercises = $attempt->getOrderedExercises();

        // Exam duration in seconds based on admin settings
        $totalTimeSeconds = max(60, ($config->duration_minutes ?? 50) * 60);
        $elapsedSeconds = $attempt->started_at ? (int) abs(now()->diffInSeconds($attempt->started_at)) : 0;
        $remainingSeconds = max(0, $totalTimeSeconds - $elapsedSeconds);

        return view('skill-test.take', compact('attempt', 'exercises', 'remainingSeconds', 'config'));
    }

    /**
     * Auto-save answer selections during the active test session.
     */
    public function saveProgress(Request $request, string $uuid)
    {
        $attempt = SkillTestAttempt::where('uuid', $uuid)->firstOrFail();

        if ($attempt->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Test is already completed.'], 400);
        }

        $answers = $request->input('answers', []);
        $durationSeconds = $request->input('duration_seconds');

        $currentAnswers = $attempt->user_answers ?? [];
        foreach ($answers as $qId => $val) {
            if ($val === null || $val === '') {
                unset($currentAnswers[$qId]);
            } else {
                $currentAnswers[$qId] = strtolower(trim($val));
            }
        }

        $updateData = ['user_answers' => $currentAnswers];
        if (is_numeric($durationSeconds) && $durationSeconds >= 0) {
            $updateData['duration_seconds'] = (int) $durationSeconds;
        }

        $attempt->update($updateData);

        return response()->json([
            'success' => true,
            'answered_count' => count($currentAnswers),
        ]);
    }

    /**
     * Submit answers and compute scores & topic-level breakdown.
     */
    public function submit(Request $request, string $uuid)
    {
        $attempt = SkillTestAttempt::where('uuid', $uuid)->firstOrFail();

        if ($attempt->status === 'completed') {
            return redirect()->route('skill-test.result', $attempt->uuid);
        }

        $submittedAnswers = $request->input('answers', []);
        $durationSeconds = $request->input('duration_seconds');
        if (!is_numeric($durationSeconds) || $durationSeconds < 0) {
            $durationSeconds = $attempt->started_at ? (int) abs(now()->diffInSeconds($attempt->started_at)) : 0;
        }

        $exercises = $attempt->getOrderedExercises();

        $score = 0;
        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $sanitizedAnswers = [];
        $topicStats = [];

        foreach ($exercises as $exercise) {
            $topicId = $exercise->topic_id;
            $topicTitle = $exercise->topic ? $exercise->topic->title : 'General';
            $topicSlug = $exercise->topic ? $exercise->topic->slug : '';

            if (!isset($topicStats[$topicId])) {
                $topicStats[$topicId] = [
                    'id' => $topicId,
                    'title' => $topicTitle,
                    'slug' => $topicSlug,
                    'total' => 0,
                    'correct' => 0,
                    'incorrect' => 0,
                    'unanswered' => 0,
                ];
            }
            $topicStats[$topicId]['total']++;

            $userAnswer = isset($submittedAnswers[$exercise->id])
                ? strtolower(trim($submittedAnswers[$exercise->id]))
                : null;

            $sanitizedAnswers[$exercise->id] = $userAnswer;
            $correctKey = strtolower(trim($exercise->correct_answer));

            if (empty($userAnswer)) {
                $unansweredCount++;
                $topicStats[$topicId]['unanswered']++;
            } elseif ($userAnswer === $correctKey) {
                $score++;
                $correctCount++;
                $topicStats[$topicId]['correct']++;
            } else {
                $incorrectCount++;
                $topicStats[$topicId]['incorrect']++;
            }
        }

        foreach ($topicStats as &$ts) {
            $ts['percentage'] = $ts['total'] > 0 ? round(($ts['correct'] / $ts['total']) * 100) : 0;
        }
        unset($ts);

        $totalQuestions = count($exercises);
        $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;

        $attempt->update([
            'user_answers' => $sanitizedAnswers,
            'score' => $score,
            'correct_count' => $correctCount,
            'incorrect_count' => $incorrectCount,
            'unanswered_count' => $unansweredCount,
            'percentage' => $percentage,
            'topic_breakdown' => array_values($topicStats),
            'status' => 'completed',
            'duration_seconds' => (int) $durationSeconds,
            'completed_at' => now(),
        ]);

        return redirect()->route('skill-test.result', $attempt->uuid)
            ->with('success', 'Skill test submitted successfully! Here is your performance report.');
    }

    /**
     * View test result and in-depth performance analysis.
     */
    public function result(Request $request, string $uuid)
    {
        $attempt = SkillTestAttempt::with('user')->where('uuid', $uuid)->firstOrFail();

        if ($attempt->status !== 'completed') {
            return redirect()->route('skill-test.take', $attempt->uuid);
        }

        $config = SkillTestConfig::getActive();
        $exercises = $attempt->getOrderedExercises();

        return view('skill-test.result', compact('attempt', 'exercises', 'config'));
    }
}
