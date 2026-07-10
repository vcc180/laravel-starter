<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Illuminate\Support\Arr;
use Modules\Blog\Models\Category;

final class CreateCategory
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(array $data): Category
    {
        $data = Arr::only($data, ['name']);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name'] ?? '');

        $category = Category::create($data);

        $this->hooks->doAction('blog.category.created', $category);

        return $category;
    }
}
