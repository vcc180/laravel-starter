<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Support\Arr;
use Modules\Blog\Models\Post;

final class CreatePost
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(array $data, ?callable $next = null): Post
    {
        $data = Arr::only($data, [
            'title',
            'slug',
            'body',
            'blog_category_id',
            'is_published',
        ]);

        $post = new Post($data);
        $post->save();

        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        $this->hooks->doAction('blog.post.created', $post);

        return $post->fresh();
    }
}
