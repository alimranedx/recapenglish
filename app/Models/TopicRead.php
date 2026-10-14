<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopicRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_id',
        'is_completed',
        'last_read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'last_read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
