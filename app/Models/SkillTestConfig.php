<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillTestConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'instructions',
        'duration_minutes',
        'total_questions',
        'pass_percentage',
        'topic_mode',
        'fixed_topic_ids',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'total_questions' => 'integer',
            'pass_percentage' => 'integer',
            'fixed_topic_ids' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get or create the singleton active configuration.
     */
    public static function getActive(): self
    {
        return static::firstOrCreate([], [
            'title' => '50-Question Master Skill Test',
            'instructions' => 'Authentic exam-style skill test. Questions are selected randomly with guaranteed topic representation.',
            'duration_minutes' => 50,
            'total_questions' => 50,
            'pass_percentage' => 60,
            'topic_mode' => 'all',
            'fixed_topic_ids' => null,
            'is_active' => true,
        ]);
    }

    /**
     * Determine if topic mode is set to fixed topics.
     */
    public function isFixedTopicMode(): bool
    {
        return $this->topic_mode === 'fixed' && !empty($this->fixed_topic_ids);
    }
}
