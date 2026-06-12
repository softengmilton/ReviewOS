<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PostVote extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $fillable = ['organization_id', 'post_id', 'voter_type', 'voter_id', 'direction', 'on_behalf_of_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function voter(): MorphTo
    {
        return $this->morphTo();
    }
}
