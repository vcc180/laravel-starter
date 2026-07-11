<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Modules\Blog\Actions\CreateCategory;
use Modules\Blog\Actions\DeleteCategory;
use Modules\Blog\Actions\ListAdminCategories;
use Modules\Blog\Actions\UpdateCategory;
use App\Http\Controllers\Controller;
use Modules\Blog\Models\Category;

class CategoryController extends Controller
{
    public function index(ListAdminCategories $action)
    {
        $items = $action->handle(request('q') ? request('q') : '');

        return view('blog::admin.categories.index', ['items' => $items]);
    }

    public function create()
    {
        return view('blog::admin.categories.create');
    }

    public function store(CreateCategory $action)
    {
        $action->handle(request()->all());

        return redirect()->route('admin.categories.index')->with('status', 'Categoria criada.');
    }

    public function edit(Category $category)
    {
        return view('blog::admin.categories.edit', ['item' => $category]);
    }

    public function update(Category $category, UpdateCategory $action)
    {
        $action->handle($category, request()->all());

        return redirect()->route('admin.categories.index')->with('status', 'Categoria atualizada.');
    }

    public function destroy(Category $category, DeleteCategory $action)
    {
        $action->handle($category);

        return redirect()->route('admin.categories.index')->with('status', 'Categoria removida.');
    }
}
