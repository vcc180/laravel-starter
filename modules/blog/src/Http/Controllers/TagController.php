<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Actions\CreateTag;
use Modules\Blog\Actions\DeleteTag;
use Modules\Blog\Actions\UpdateTag;
use Modules\Blog\Http\Requests\Admin\TagRequest;
use Modules\Blog\Models\Tag;

class TagController extends \App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        return view('blog::admin.tags.index', ['items' => Tag::query()->orderByDesc('id')->paginate(20)]);
    }

    public function create()
    {
        return view('blog::admin.tags.create');
    }

    public function store(TagRequest $request, CreateTag $action)
    {
        $action->handle($request->validated());

        return redirect()->route('admin.tags.index')->with('status', 'Tag criada.');
    }

    public function edit(Tag $tag)
    {
        return view('blog::admin.tags.edit', ['item' => $tag]);
    }

    public function update(TagRequest $request, Tag $tag, UpdateTag $action)
    {
        $action->handle($tag, $request->validated());

        return redirect()->route('admin.tags.index')->with('status', 'Tag atualizada.');
    }

    public function destroy(Tag $tag, DeleteTag $action)
    {
        $action->handle($tag);

        return back()->with('status', 'Tag removida.');
    }
}
