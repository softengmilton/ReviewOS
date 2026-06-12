<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostStatus extends Model
{
    use BelongsToOrganization, HasFactory, HasUuid;

    protected $fillable = ['organization_id', 'name', 'color', 'type', 'sort_order', 'notify_subscribers'];

    protected function casts(): array
    {
        return [
            'notify_subscribers' => 'boolean',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'status_id');
    }
}
