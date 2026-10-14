<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Part;
use App\Models\Topic;
use App\Models\TopicRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReaderController extends Controller
{
    /**
     * Display the book overview / table of contents.
     */
    public function index()
    {
        $parts = Part::with(['chapters' => function ($query) {
            $query->where('is_published', true)->with(['topics' => function ($q) {
                $q->where('is_published', true)->withCount('exercises');
            }]);
        }])->where('is_active', true)->orderBy('order')->get();

        $user = Auth::user();
        $completedTopicIds = [];
        $bookmarkedTopicIds = [];
        $lastTopic = null;

        if ($user) {
            $completedTopicIds = TopicRead::where('user_id', $user->id)
                ->where('is_completed', true)
                ->pluck('topic_id')
                ->toArray();

            $bookmarkedTopicIds = Bookmark::where('user_id', $user->id)
                ->pluck('topic_id')
                ->toArray();

            if ($user->last_topic_id) {
                $lastTopic = Topic::with('chapter.part')->find($user->last_topic_id);
            }
        }

        $totalTopicsCount = Topic::where('is_published', true)->count();
        $totalExercisesCount = \App\Models\Exercise::count();
        $userProgressPercent = ($user && $totalTopicsCount > 0)
            ? round((count($completedTopicIds) / $totalTopicsCount) * 100)
            : 0;

        return view('reader.index', compact('parts', 'user', 'completedTopicIds', 'bookmarkedTopicIds', 'lastTopic', 'totalTopicsCount', 'totalExercisesCount', 'userProgressPercent'));
    }

    /**
     * Display an individual topic for reading.
     */
    public function showTopic(string $slug)
    {
        $topic = Topic::with(['chapter.part', 'exercises'])->where('slug', $slug)->firstOrFail();

        $user = Auth::user();
        $isCompleted = false;
        $isBookmarked = false;

        // Auto-save user's last visited topic
        if ($user) {
            $user->update([
                'last_topic_id' => $topic->id,
                'last_read_at' => now(),
            ]);

            $topicRead = TopicRead::firstOrCreate(
                ['user_id' => $user->id, 'topic_id' => $topic->id],
                ['last_read_at' => now(), 'is_completed' => false]
            );
            $topicRead->update(['last_read_at' => now()]);
            $isCompleted = $topicRead->is_completed;

            $isBookmarked = Bookmark::where('user_id', $user->id)->where('topic_id', $topic->id)->exists();
        }

        // Full book syllabus for the side navigation drawer
        $parts = Part::with(['chapters' => function ($query) {
            $query->where('is_published', true)->with(['topics' => function ($q) {
                $q->where('is_published', true);
            }]);
        }])->where('is_active', true)->orderBy('order')->get();

        $completedTopicIds = $user
            ? TopicRead::where('user_id', $user->id)->where('is_completed', true)->pluck('topic_id')->toArray()
            : [];

        $previousTopic = $topic->getPreviousTopic();
        $nextTopic = $topic->getNextTopic();

        return view('reader.topic', compact('topic', 'parts', 'isCompleted', 'isBookmarked', 'completedTopicIds', 'previousTopic', 'nextTopic'));
    }

    /**
     * Toggle topic completion status.
     */
    public function toggleComplete(Request $request, int $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('info', 'Please log in to track your reading progress.');
        }

        $topic = Topic::findOrFail($id);
        $read = TopicRead::firstOrCreate(['user_id' => $user->id, 'topic_id' => $topic->id]);
        $read->is_completed = !$read->is_completed;
        $read->last_read_at = now();
        $read->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $read->is_completed,
                'message' => $read->is_completed ? 'Topic marked as completed!' : 'Marked as uncompleted.',
            ]);
        }

        return back()->with('success', $read->is_completed ? 'Topic marked as completed!' : 'Marked as uncompleted.');
    }

    /**
     * Toggle bookmark for the topic.
     */
    public function toggleBookmark(Request $request, int $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('info', 'Please log in to bookmark topics.');
        }

        $topic = Topic::findOrFail($id);
        $bookmark = Bookmark::where('user_id', $user->id)->where('topic_id', $topic->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
            $msg = 'Removed from saved topics.';
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'topic_id' => $topic->id,
                'note' => $request->input('note'),
            ]);
            $bookmarked = true;
            $msg = 'Saved to your bookmarked topics!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_bookmarked' => $bookmarked,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * List user's bookmarked topics.
     */
    public function bookmarks()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $bookmarks = Bookmark::with('topic.chapter.part')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('reader.bookmarks', compact('bookmarks'));
    }

    /**
     * Search topics, chapters, and practice questions.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $results = [];
        $matchingExercises = [];

        if (!empty($query)) {
            $results = Topic::with('chapter.part')
                ->where('is_published', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('mnemonics', 'like', "%{$query}%");
                })
                ->take(20)
                ->get();

            $matchingExercises = \App\Models\Exercise::with('topic.chapter.part')
                ->where(function ($q) use ($query) {
                    $q->where('question', 'like', "%{$query}%")
                      ->orWhere('explanation', 'like', "%{$query}%")
                      ->orWhere('exam_reference', 'like', "%{$query}%");
                })
                ->take(30)
                ->get();
        }

        return view('reader.search', compact('query', 'results', 'matchingExercises'));
    }
}
