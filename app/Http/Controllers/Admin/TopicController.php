<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    /**
     * Display a listing of topics with filters.
     */
    public function index(Request $request)
    {
        $chapterId = $request->input('chapter_id');

        $query = Topic::with('chapter.part')->orderBy('order', 'asc');

        if ($chapterId) {
            $query->where('chapter_id', $chapterId);
        }

        $topics = $query->paginate(20);
        $chapters = Chapter::orderBy('order', 'asc')->get();

        return view('admin.topics.index', compact('topics', 'chapters', 'chapterId'));
    }

    /**
     * Show the form for creating a new topic.
     */
    public function create()
    {
        $chapters = Chapter::with('part')->orderBy('order', 'asc')->get();

        return view('admin.topics.create', compact('chapters'));
    }

    /**
     * Store a newly created topic.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => ['required', 'exists:chapters,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'mnemonics' => ['nullable', 'string'],
            'estimated_read_time' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $uniqueSlug = $slug;
        $counter = 1;
        while (Topic::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = "{$slug}-{$counter}";
            $counter++;
        }

        $topic = Topic::create([
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'slug' => $uniqueSlug,
            'content' => $validated['content'],
            'mnemonics' => $validated['mnemonics'],
            'estimated_read_time' => $validated['estimated_read_time'],
            'order' => $validated['order'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.topics.edit', $topic->id)->with('success', 'Topic created successfully! You can now add exercises or edit details.');
    }

    /**
     * Show the form for editing the specified topic.
     */
    public function edit(int $id)
    {
        $topic = Topic::with(['chapter.part', 'exercises'])->findOrFail($id);
        $chapters = Chapter::with('part')->orderBy('order', 'asc')->get();

        return view('admin.topics.edit', compact('topic', 'chapters'));
    }

    /**
     * Update the specified topic.
     */
    public function update(Request $request, int $id)
    {
        $topic = Topic::findOrFail($id);

        $validated = $request->validate([
            'chapter_id' => ['required', 'exists:chapters,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'mnemonics' => ['nullable', 'string'],
            'estimated_read_time' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $topic->update([
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'mnemonics' => $validated['mnemonics'],
            'estimated_read_time' => $validated['estimated_read_time'],
            'order' => $validated['order'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.topics.edit', $topic->id)->with('success', 'Topic updated successfully.');
    }

    /**
     * Delete the specified topic.
     */
    public function destroy(int $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();

        return redirect()->route('admin.topics.index')->with('success', 'Topic deleted successfully.');
    }

    /**
     * Add a practice exercise to a topic.
     */
    public function addExercise(Request $request, int $topicId)
    {
        $topic = Topic::findOrFail($topicId);

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['nullable', 'string'],
            'option_d' => ['nullable', 'string'],
            'correct_answer' => ['required', 'in:a,b,c,d'],
            'explanation' => ['nullable', 'string'],
            'exam_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $options = [
            'a) ' . $validated['option_a'],
            'b) ' . $validated['option_b'],
        ];
        if (!empty($validated['option_c'])) {
            $options[] = 'c) ' . $validated['option_c'];
        }
        if (!empty($validated['option_d'])) {
            $options[] = 'd) ' . $validated['option_d'];
        }

        $nextOrder = $topic->exercises()->max('order') + 1;

        Exercise::create([
            'topic_id' => $topic->id,
            'question' => $validated['question'],
            'options' => $options,
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'],
            'exam_reference' => $validated['exam_reference'],
            'order' => $nextOrder,
        ]);

        return back()->with('success', 'Practice question added successfully!');
    }

    /**
     * Delete a practice exercise.
     */
    public function deleteExercise(int $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->delete();

        return back()->with('success', 'Practice question removed.');
    }
}
