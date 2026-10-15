<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillTestConfig;
use App\Models\Topic;
use Illuminate\Http\Request;

class SkillTestConfigController extends Controller
{
    /**
     * Show the exam configuration settings form.
     */
    public function edit()
    {
        $config = SkillTestConfig::getActive();

        $topics = Topic::with('chapter.part')
            ->where('is_published', true)
            ->withCount('exercises')
            ->orderBy('order')
            ->get();

        return view('admin.skill-tests.settings', compact('config', 'topics'));
    }

    /**
     * Update the exam configuration settings.
     */
    public function update(Request $request)
    {
        $config = SkillTestConfig::getActive();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:240'],
            'total_questions' => ['required', 'integer', 'min:5', 'max:200'],
            'pass_percentage' => ['required', 'integer', 'min:20', 'max:100'],
            'topic_mode' => ['required', 'in:all,fixed'],
            'fixed_topic_ids' => ['nullable', 'array'],
            'fixed_topic_ids.*' => ['integer', 'exists:topics,id'],
            'instructions' => ['nullable', 'string', 'max:1500'],
        ]);

        if ($validated['topic_mode'] === 'fixed' && empty($validated['fixed_topic_ids'])) {
            return back()->withErrors([
                'fixed_topic_ids' => 'Please select at least one topic when choosing "Fixed / Selected Topics" mode.',
            ])->withInput();
        }

        $config->update([
            'title' => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'total_questions' => $validated['total_questions'],
            'pass_percentage' => $validated['pass_percentage'],
            'topic_mode' => $validated['topic_mode'],
            'fixed_topic_ids' => $validated['topic_mode'] === 'fixed' ? array_map('intval', $validated['fixed_topic_ids']) : null,
            'instructions' => $validated['instructions'] ?? null,
        ]);

        return redirect()->route('admin.skill-test-settings.edit')
            ->with('success', 'Skill test exam configuration updated successfully! Changes take effect immediately for all new tests.');
    }
}
