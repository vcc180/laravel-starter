<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Modules\Blog\Models\Post;

final class GetPublishedPost
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Post $post): Post
    {
        if (!$post->is_published) {
            abort(404);
        }

        $this->hooks->doAction('blog.post.viewed', $post);

        return $post;
    }
}
