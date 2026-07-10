<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Actions\CreateTag;
use Modules\Blog\Actions\DeleteTag;
use Modules\Blog\Actions\UpdateTag;
use Modules\Blog\Models\Tag;

class TagController extends \App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        $items = Tag::query()->orderByDesc('id')->paginate(20);

        return view('blog::admin.tags.index', ['items' => $items]);
    }

    public function create()
    {
        return view('blog::admin.tags.create');
    }

    public function store(Request $request, CreateTag $action)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_tags,slug'],
        ]);

        $action->handle($data);

        return redirect()->route('admin.tags.index')->with('status', 'Tag criada.');
    }

    public function edit(Tag $tag)
    {
        return view('blog::admin.tags.edit', ['item' => $tag]);
    }

    public function update(Request $request, Tag $tag, UpdateTag $action)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_tags,slug,' . $tag->id],
        ]);

        $action->handle($tag, $data);

        return redirect()->route('admin.tags.index')->with('status', 'Tag atualizada.');
    }

    public function destroy(Tag $tag, DeleteTag $action)
    {
        $action->handle($tag);

        return back()->with('status', 'Tag removida.');
    }
}
