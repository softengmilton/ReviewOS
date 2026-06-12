<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PostSubscriber extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $fillable = ['organization_id', 'post_id', 'subscriber_type', 'subscriber_id', 'notify_status_change', 'notify_comment'];

    protected function casts(): array
    {
        return [
            'notify_status_change' => 'boolean',
            'notify_comment' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }
}
