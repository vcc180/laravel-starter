<?php

namespace Modules\Blog\Listeners;

use Modules\Blog\Models\Post;

class PostDeletedListener
{
    public function handle(Post $post): void
    {
        $post->tags()->detach();
    }
}
