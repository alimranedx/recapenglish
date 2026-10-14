<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'slug',
        'content',
        'mnemonics',
        'estimated_read_time',
        'order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'order' => 'integer',
            'estimated_read_time' => 'integer',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class)->orderBy('order', 'asc');
    }

    public function topicReads(): HasMany
    {
        return $this->hasMany(TopicRead::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function getPreviousTopic(): ?Topic
    {
        // Try previous in same chapter
        $prev = Topic::where('chapter_id', $this->chapter_id)
            ->where('order', '<', $this->order)
            ->where('is_published', true)
            ->orderBy('order', 'desc')
            ->first();

        if ($prev) {
            return $prev;
        }

        // Try previous chapter's last topic
        $prevChapter = Chapter::where('order', '<', $this->chapter->order)
            ->where('is_published', true)
            ->orderBy('order', 'desc')
            ->first();

        if ($prevChapter) {
            return $prevChapter->topics()->where('is_published', true)->orderBy('order', 'desc')->first();
        }

        return null;
    }

    public function getNextTopic(): ?Topic
    {
        // Try next in same chapter
        $next = Topic::where('chapter_id', $this->chapter_id)
            ->where('order', '>', $this->order)
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->first();

        if ($next) {
            return $next;
        }

        // Try next chapter's first topic
        $nextChapter = Chapter::where('order', '>', $this->chapter->order)
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextChapter) {
            return $nextChapter->topics()->where('is_published', true)->orderBy('order', 'asc')->first();
        }

        return null;
    }
}
