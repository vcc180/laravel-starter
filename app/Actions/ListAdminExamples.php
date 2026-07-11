<?php

namespace App\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Example;

final class ListAdminExamples
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(?string $query = null, int $perPage = 20): LengthAwarePaginator
    {
        $items = Example::query()
            ->when($query, function (Builder $qb, string $q) {
                $qb->where('name', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%");
            })
            ->latest('id')
            ->paginate($perPage);

        $this->hooks->doAction('admin.examples.listed', $items);

        return $items;
    }
}
