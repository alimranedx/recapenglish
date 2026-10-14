<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::withCount('chapters')->orderBy('order', 'asc')->get();

        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Part::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $validated['order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.parts.index')->with('success', 'Book Part created successfully.');
    }

    public function edit(int $id)
    {
        $part = Part::findOrFail($id);

        return view('admin.parts.edit', compact('part'));
    }

    public function update(Request $request, int $id)
    {
        $part = Part::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $part->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $validated['order'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.parts.index')->with('success', 'Book Part updated successfully.');
    }

    public function destroy(int $id)
    {
        $part = Part::findOrFail($id);
        $part->delete();

        return redirect()->route('admin.parts.index')->with('success', 'Book Part removed.');
    }
}
