<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\Topic;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of questions/exercises with filters.
     */
    public function index(Request $request)
    {
        $topicId = $request->input('topic_id');
        $chapterId = $request->input('chapter_id');
        $search = $request->input('q');

        $query = Exercise::with('topic.chapter.part')->orderBy('id', 'desc');

        if ($topicId) {
            $query->where('topic_id', $topicId);
        } elseif ($chapterId) {
            $query->whereHas('topic', function ($q) use ($chapterId) {
                $q->where('chapter_id', $chapterId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('explanation', 'like', "%{$search}%")
                  ->orWhere('exam_reference', 'like', "%{$search}%");
            });
        }

        $exercises = $query->paginate(15);
        $chapters = Chapter::with('topics')->orderBy('order', 'asc')->get();
        $topics = Topic::orderBy('order', 'asc')->get();

        return view('admin.exercises.index', compact('exercises', 'chapters', 'topics', 'topicId', 'chapterId', 'search'));
    }

    /**
     * Show form to create a new exercise.
     */
    public function create(Request $request)
    {
        $preselectedTopicId = $request->input('topic_id');
        $topics = Topic::with('chapter.part')->orderBy('order', 'asc')->get();

        return view('admin.exercises.create', compact('topics', 'preselectedTopicId'));
    }

    /**
     * Store a newly created exercise.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_answer' => ['required', 'in:a,b,c,d'],
            'explanation' => ['nullable', 'string'],
            'exam_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $options = [
            'a) ' . $this->cleanOptionPrefix($validated['option_a']),
            'b) ' . $this->cleanOptionPrefix($validated['option_b']),
            'c) ' . $this->cleanOptionPrefix($validated['option_c']),
            'd) ' . $this->cleanOptionPrefix($validated['option_d']),
        ];

        $topic = Topic::findOrFail($validated['topic_id']);
        $nextOrder = (int) $topic->exercises()->max('order') + 1;

        $exercise = Exercise::create([
            'topic_id' => $topic->id,
            'question' => $validated['question'],
            'options' => $options,
            'correct_answer' => strtolower($validated['correct_answer']),
            'explanation' => $validated['explanation'],
            'exam_reference' => $validated['exam_reference'],
            'order' => $nextOrder,
        ]);

        if ($request->input('redirect_to') === 'topic_edit') {
            return redirect()->route('admin.topics.edit', $topic->id)->with('success', 'Practice question added successfully!');
        }

        return redirect()->route('admin.exercises.index')->with('success', 'Practice question created successfully!');
    }

    /**
     * Show form to edit an exercise.
     */
    public function edit(int $id)
    {
        $exercise = Exercise::with('topic.chapter.part')->findOrFail($id);
        $topics = Topic::with('chapter.part')->orderBy('order', 'asc')->get();

        // Extract option texts without prefixes
        $parsedOptions = ['a' => '', 'b' => '', 'c' => '', 'd' => ''];
        if (is_array($exercise->options)) {
            foreach ($exercise->options as $index => $opt) {
                if (preg_match('/^([a-d])[\)\.]\s*(.*)/i', $opt, $matches)) {
                    $key = strtolower($matches[1]);
                    $parsedOptions[$key] = $matches[2];
                } else {
                    $keys = ['a', 'b', 'c', 'd'];
                    $key = $keys[$index] ?? 'a';
                    $parsedOptions[$key] = $opt;
                }
            }
        }

        return view('admin.exercises.edit', compact('exercise', 'topics', 'parsedOptions'));
    }

    /**
     * Update an exercise.
     */
    public function update(Request $request, int $id)
    {
        $exercise = Exercise::findOrFail($id);

        $validated = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_answer' => ['required', 'in:a,b,c,d'],
            'explanation' => ['nullable', 'string'],
            'exam_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $options = [
            'a) ' . $this->cleanOptionPrefix($validated['option_a']),
            'b) ' . $this->cleanOptionPrefix($validated['option_b']),
            'c) ' . $this->cleanOptionPrefix($validated['option_c']),
            'd) ' . $this->cleanOptionPrefix($validated['option_d']),
        ];

        $exercise->update([
            'topic_id' => $validated['topic_id'],
            'question' => $validated['question'],
            'options' => $options,
            'correct_answer' => strtolower($validated['correct_answer']),
            'explanation' => $validated['explanation'],
            'exam_reference' => $validated['exam_reference'],
        ]);

        if ($request->filled('return_url')) {
            return redirect($request->input('return_url'))->with('success', 'Question, correct answer key, and explanation updated successfully!');
        }

        if ($request->input('redirect_to') === 'topic_edit') {
            return redirect()->route('admin.topics.edit', $exercise->topic_id)->with('success', 'Practice question updated successfully!');
        }

        return redirect()->route('admin.exercises.index')->with('success', 'Question & answer updated successfully!');
    }

    /**
     * Remove an exercise.
     */
    public function destroy(int $id)
    {
        $exercise = Exercise::findOrFail($id);
        $topicId = $exercise->topic_id;
        $exercise->delete();

        return back()->with('success', 'Question removed successfully.');
    }

    /**
     * Clean option text from prefixes like "a)", "B.", etc.
     */
    private function cleanOptionPrefix(string $text): string
    {
        return preg_replace('/^[a-d][\)\.]\s*/i', '', trim($text));
    }
}
