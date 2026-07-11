<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Blog\Models\Category;

final class ListAdminCategories
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(?string $search = null, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? 20;

        $items = Category::query()
            ->when($search, fn ($qb) => $qb->where('name', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate($perPage);

        $this->hooks->doAction('blog.admin.categories.listed', $items);

        return $items;
    }
}
