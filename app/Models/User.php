<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'last_topic_id',
        'last_read_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_read_at' => 'datetime',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }

    public function lastTopic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'last_topic_id');
    }

    public function topicReads(): HasMany
    {
        return $this->hasMany(TopicRead::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function completedTopicsCount(): int
    {
        return $this->topicReads()->where('is_completed', true)->count();
    }

    public function skillTestAttempts(): HasMany
    {
        return $this->hasMany(SkillTestAttempt::class)->latest();
    }
}
