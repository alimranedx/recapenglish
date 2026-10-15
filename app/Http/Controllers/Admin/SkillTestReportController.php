<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillTestAttempt;
use Illuminate\Http\Request;

class SkillTestReportController extends Controller
{
    /**
     * Display a listing of all student skill test attempts.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'completed');
        $search = $request->input('search');
        $filter = $request->input('filter'); // 'high', 'low', 'all'

        $query = SkillTestAttempt::with('user')->latest();

        if ($status && in_array($status, ['completed', 'in_progress'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($filter === 'high') {
            $query->where('percentage', '>=', 80);
        } elseif ($filter === 'low') {
            $query->where('percentage', '<', 50);
        }

        $attempts = $query->paginate(20)->withQueryString();

        $stats = [
            'total_completed' => SkillTestAttempt::where('status', 'completed')->count(),
            'avg_score' => round(SkillTestAttempt::where('status', 'completed')->avg('score') ?? 0, 1),
            'avg_percentage' => round(SkillTestAttempt::where('status', 'completed')->avg('percentage') ?? 0, 1),
            'high_performers' => SkillTestAttempt::where('status', 'completed')->where('percentage', '>=', 80)->count(),
        ];

        return view('admin.skill-tests.index', compact('attempts', 'stats', 'status', 'search', 'filter'));
    }

    /**
     * Delete a test attempt record.
     */
    public function destroy(int $id)
    {
        $attempt = SkillTestAttempt::findOrFail($id);
        $attempt->delete();

        return back()->with('success', 'Skill test record deleted successfully.');
    }
}
