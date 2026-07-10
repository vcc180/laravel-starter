<?php

namespace Modules\Blog\Actions;

use Illuminate\Support\Arr;
use Modules\Blog\Models\Category;

final class UpdateCategory
{
    public function handle(Category $category, array $data): Category
    {
        $data = Arr::only($data, ['name']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name'] ?? '');

        $category->update($data);

        return $category->fresh();
    }
}
