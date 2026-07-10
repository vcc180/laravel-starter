<?php

namespace Modules\Blog\Actions;

use Core\Contracts\HookInterface;
use Modules\Blog\Models\Category;

final class DeleteCategory
{
    public function __construct(private HookInterface $hooks) {}

    public function handle(Category $category): void
    {
        $this->hooks->doAction('blog.category.deleting', $category);

        $category->delete();

        $this->hooks->doAction('blog.category.deleted', $category);
    }
}
