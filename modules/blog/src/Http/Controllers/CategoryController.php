<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data = array_merge($data, ['slug' => str_slug($data['name'])]);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('status', 'Categoria criada.');
    }

    public function edit(Category $category)
    {
        return view('blog::admin.categories.edit', ['item' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update(array_merge($data, ['slug' => str_slug($data['name'])]));

        return redirect()->route('admin.categories.index')->with('status', 'Categoria atualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('status', 'Categoria removida.');
    }
}
