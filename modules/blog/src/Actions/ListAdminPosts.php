<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\Models\Post;

final class ListAdminPosts
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(?string $query = null, int $perPage = 20): LengthAwarePaginator
    {
        $items = Post::query()
            ->when($query, function (Builder $qb, string $q) {
                $qb->where('title', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate($perPage);

        $this->hooks->doAction('blog.admin.posts.listed', $items);

        return $items;
    }
}
