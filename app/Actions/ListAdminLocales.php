<?php

namespace App\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Locale;

final class ListAdminLocales
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(?string $query = null, int $perPage = 20): LengthAwarePaginator
    {
        $items = Locale::query()
            ->when($query, function (Builder $qb, string $q) {
                $qb->where('locale', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            })
            ->latest('id')
            ->paginate($perPage);

        $this->hooks->doAction('admin.locales.listed', $items);

        return $items;
    }
}
