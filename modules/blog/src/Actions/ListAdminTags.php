<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\Models\Tag;

final class ListAdminTags
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(?string $search = null, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? 20;

        $items = Tag::query()
            ->when($search, fn ($qb) => $qb->where('name', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate($perPage);

        $this->hooks->doAction('blog.admin.tags.listed', $items);

        return $items;
    }
}
