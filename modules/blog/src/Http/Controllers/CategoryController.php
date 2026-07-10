<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Actions\CreateCategory;
use Modules\Blog\Actions\DeleteCategory;
use Modules\Blog\Actions\UpdateCategory;
use Modules\Blog\Http\Requests\Admin\CategoryRequest;
use Modules\Blog\Models\Category;

class CategoryController extends \App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        return view('blog::admin.categories.index', ['items' => Category::query()->orderByDesc('id')->paginate(20)]);
    }

    public function create()
    {
        return view('blog::admin.categories.create');
    }

    public function store(CategoryRequest $request, CreateCategory $action)
    {
        $action->handle($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Categoria criada.');
    }

    public function edit(Category $category)
    {
        return view('blog::admin.categories.edit', ['item' => $category]);
    }

    public function update(CategoryRequest $request, Category $category, UpdateCategory $action)
    {
        $action->handle($category, $request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Categoria atualizada.');
    }

    public function destroy(Category $category, DeleteCategory $action)
    {
        $action->handle($category);

        return back()->with('status', 'Categoria removida.');
    }
}
