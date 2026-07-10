<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Support\Arr;
use Modules\Blog\Models\Post;

final class UpdatePost
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Post $post, array $data, ?callable $next = null): Post
    {
        $data = Arr::only($data, [
            'title',
            'slug',
            'body',
            'blog_category_id',
            'is_published',
        ]);

        $post->fill($data);
        $post->save();

        if (array_key_exists('tags', $data)) {
            $post->tags()->sync($data['tags'] ?? []);
        }

        $this->hooks->doAction('blog.post.updated', $post->fresh());

        return $post->fresh();
    }
}
