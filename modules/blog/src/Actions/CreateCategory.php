<?php

namespace Modules\Blog\Actions;

use Illuminate\Support\Arr;
use Modules\Blog\Models\Category;

final class CreateCategory
{
    public function handle(array $data): Category
    {
        $data = Arr::only($data, ['name']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name'] ?? '');

        return Category::create($data);
    }
}
