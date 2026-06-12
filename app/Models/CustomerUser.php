<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;

class CustomerUser extends Model
{
    use BelongsToOrganization, HasFactory, HasUuid, Notifiable;

    protected $fillable = ['email', 'name', 'external_id', 'avatar_url', 'metadata', 'segment_ids'];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'segment_ids' => 'array',
        ];
    }

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'author');
    }
}
