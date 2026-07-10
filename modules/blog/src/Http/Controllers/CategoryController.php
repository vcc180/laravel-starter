<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Actions\CreateCategory;
use Modules\Blog\Actions\DeleteCategory;
use Modules\Blog\Actions\UpdateCategory;
use Modules\Blog\Models\Category;

class CategoryController extends \App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        $items = Category::query()->orderByDesc('id')->paginate(20);

        return view('blog::admin.categories.index', ['items' => $items]);
    }

    public function create()
    {
        return view('blog::admin.categories.create');
    }

    public function store(Request $request, CreateCategory $action)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $action->handle($data);

        return redirect()->route('admin.categories.index')->with('status', 'Categoria criada.');
    }

    public function edit(Category $category)
    {
        return view('blog::admin.categories.edit', ['item' => $category]);
    }

    public function update(Request $request, Category $category, UpdateCategory $action)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $action->handle($category, $data);

        return redirect()->route('admin.categories.index')->with('status', 'Categoria atualizada.');
    }

    public function destroy(Category $category, DeleteCategory $action)
    {
        $action->handle($category);

        return back()->with('status', 'Categoria removida.');
    }
}
