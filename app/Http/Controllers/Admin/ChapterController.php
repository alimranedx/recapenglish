<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function index()
    {
        $chapters = Chapter::with(['part', 'topics'])->orderBy('order', 'asc')->paginate(20);

        return view('admin.chapters.index', compact('chapters'));
    }

    public function create()
    {
        $parts = Part::orderBy('order', 'asc')->get();

        return view('admin.chapters.create', compact('parts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_id' => ['required', 'exists:parts,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $uniqueSlug = $slug;
        $counter = 1;
        while (Chapter::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = "{$slug}-{$counter}";
            $counter++;
        }

        Chapter::create([
            'part_id' => $validated['part_id'],
            'title' => $validated['title'],
            'slug' => $uniqueSlug,
            'summary' => $validated['summary'],
            'order' => $validated['order'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter created successfully.');
    }

    public function edit(int $id)
    {
        $chapter = Chapter::findOrFail($id);
        $parts = Part::orderBy('order', 'asc')->get();

        return view('admin.chapters.edit', compact('chapter', 'parts'));
    }

    public function update(Request $request, int $id)
    {
        $chapter = Chapter::findOrFail($id);

        $validated = $request->validate([
            'part_id' => ['required', 'exists:parts,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $chapter->update([
            'part_id' => $validated['part_id'],
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'order' => $validated['order'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated successfully.');
    }

    public function destroy(int $id)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->delete();

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter removed successfully.');
    }
}
