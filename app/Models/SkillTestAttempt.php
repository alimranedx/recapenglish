<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class SkillTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'session_id',
        'question_ids',
        'user_answers',
        'total_questions',
        'score',
        'correct_count',
        'incorrect_count',
        'unanswered_count',
        'percentage',
        'topic_breakdown',
        'status',
        'duration_seconds',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'question_ids' => 'array',
            'user_answers' => 'array',
            'topic_breakdown' => 'array',
            'percentage' => 'float',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exercises in the exact sequence preserved in question_ids.
     */
    public function getOrderedExercises(): Collection
    {
        if (empty($this->question_ids)) {
            return collect();
        }

        $exercises = Exercise::with('topic.chapter.part')
            ->whereIn('id', $this->question_ids)
            ->get()
            ->keyBy('id');

        $ordered = collect();
        foreach ($this->question_ids as $id) {
            if ($exercises->has($id)) {
                $ordered->push($exercises->get($id));
            }
        }

        return $ordered;
    }

    /**
     * Generate randomized questions across topics based on active settings.
     * If fixed topic mode is active, only questions from fixed topics are selected.
     * Otherwise, guarantees every single published topic is represented without missing any topic.
     * Randomizes the questions for each test attempt.
     */
    public static function generateRandomSkillTestQuestionIds(?int $totalQuestions = null, ?array $allowedTopicIds = null): array
    {
        $config = SkillTestConfig::getActive();
        $totalQuestions = $totalQuestions ?? ($config->total_questions ?? 50);

        if ($allowedTopicIds === null && $config->isFixedTopicMode()) {
            $allowedTopicIds = $config->fixed_topic_ids;
        }

        $query = Topic::where('is_published', true)
            ->has('exercises')
            ->withCount('exercises');

        if (!empty($allowedTopicIds)) {
            $query->whereIn('id', $allowedTopicIds);
        }

        $topics = $query->get();

        // Fallback to all published topics if custom topic filtering returned 0 topics
        if ($topics->isEmpty() && !empty($allowedTopicIds)) {
            $topics = Topic::where('is_published', true)
                ->has('exercises')
                ->withCount('exercises')
                ->get();
        }

        $topicCount = $topics->count();
        if ($topicCount === 0) {
            return [];
        }

        // If total questions requested is fewer than topic count, sample $totalQuestions topics
        if ($totalQuestions < $topicCount) {
            $chosenTopics = $topics->random($totalQuestions);
            $selectedIds = [];
            foreach ($chosenTopics as $t) {
                $exerciseId = Exercise::where('topic_id', $t->id)->inRandomOrder()->value('id');
                if ($exerciseId) {
                    $selectedIds[] = $exerciseId;
                }
            }
            shuffle($selectedIds);
            return $selectedIds;
        }

        // Base quota per topic to guarantee every single topic is included
        $baseQuota = intdiv($totalQuestions, $topicCount);
        $remainder = $totalQuestions % $topicCount;

        $quotas = [];
        foreach ($topics as $topic) {
            $quotas[$topic->id] = min($baseQuota, $topic->exercises_count);
        }

        // Distribute remainder randomly among topics that still have available exercises
        $shuffledTopicIds = $topics->pluck('id')->shuffle()->toArray();
        $remainderAllocated = 0;

        foreach ($shuffledTopicIds as $topicId) {
            if ($remainderAllocated >= $remainder) {
                break;
            }
            $topic = $topics->firstWhere('id', $topicId);
            if ($quotas[$topicId] < $topic->exercises_count) {
                $quotas[$topicId]++;
                $remainderAllocated++;
            }
        }

        // If any quota remains unallocated due to exercises_count constraints, backfill from largest pools
        $currentSum = array_sum($quotas);
        if ($currentSum < $totalQuestions) {
            $needed = $totalQuestions - $currentSum;
            foreach ($topics->sortByDesc('exercises_count') as $topic) {
                $available = $topic->exercises_count - $quotas[$topic->id];
                if ($available > 0) {
                    $take = min($available, $needed);
                    $quotas[$topic->id] += $take;
                    $needed -= $take;
                    if ($needed <= 0) {
                        break;
                    }
                }
            }
        }

        // Fetch random questions for each topic
        $selectedExerciseIds = [];
        foreach ($quotas as $topicId => $count) {
            if ($count > 0) {
                $ids = Exercise::where('topic_id', $topicId)
                    ->inRandomOrder()
                    ->limit($count)
                    ->pluck('id')
                    ->toArray();
                $selectedExerciseIds = array_merge($selectedExerciseIds, $ids);
            }
        }

        // Interleave / shuffle so topics are distributed naturally throughout the test
        shuffle($selectedExerciseIds);

        return $selectedExerciseIds;
    }
}
