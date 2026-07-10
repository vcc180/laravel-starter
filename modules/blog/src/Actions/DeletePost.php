<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Modules\Blog\Models\Post;

final class DeletePost
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Post $post, ?callable $next = null): void
    {
        $this->hooks->doAction('blog.post.deleting', $post);

        $post->tags()->detach();
        $post->delete();

        $this->hooks->doAction('blog.post.deleted', $post);
    }
}
