<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Support\Arr;
use Modules\Blog\Models\Category;

final class UpdateCategory
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Category $category, array $data): Category
    {
        $data = Arr::only($data, ['name']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name'] ?? '');

        $category->update($data);
        $category = $category->fresh();

        $this->hooks->doAction('blog.category.updated', $category);

        return $category;
    }
}
