<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\Models\Post;

final class ListPublishedPosts
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(?string $query = null, int $perPage = 10): \Illuminate\Contracts\Pagination\Paginator
    {
        $items = Post::query()
            ->where('is_published', true)
            ->when($query, function (Builder $qb, string $q) {
                $qb->where('title', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate($perPage);

        $this->hooks->doAction('blog.posts.listed', $items);

        return $items;
    }
}
