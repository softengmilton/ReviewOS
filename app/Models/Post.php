<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use BelongsToOrganization, HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'board_id',
        'status_id',
        'title',
        'content',
        'is_internal',
        'is_hidden',
        'is_pinned',
        'eta',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
            'is_hidden' => 'boolean',
            'is_pinned' => 'boolean',
            'eta' => 'date',
        ];
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PostStatus::class, 'status_id');
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PostVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(PostSubscriber::class);
    }

    public function subscriptions(): MorphMany
    {
        return $this->morphMany(PostSubscriber::class, 'subscriber');
    }
}
