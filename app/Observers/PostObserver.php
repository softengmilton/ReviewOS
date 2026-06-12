<?php

namespace App\Observers;

use App\Models\Post;
use App\Notifications\PostStatusChangedNotification;

class PostObserver
{
    public function updated(Post $post): void
    {
        if (! $post->wasChanged('status_id')) {
            return;
        }

        $post->loadMissing(['status', 'subscribers.subscriber']);

        if ($post->status && ! $post->status->notify_subscribers) {
            return;
        }

        foreach ($post->subscribers as $subscription) {
            if ($subscription->notify_status_change && $subscription->subscriber) {
                $subscription->subscriber->notify(new PostStatusChangedNotification($post));
            }
        }
    }
}
