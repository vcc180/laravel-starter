<?php

namespace Modules\Blog\Actions;

use Modules\Blog\Models\Category;

final class DeleteCategory
{
    public function handle(Category $category): void
    {
        $category->delete();
    }
}
