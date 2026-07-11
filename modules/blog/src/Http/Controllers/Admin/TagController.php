<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Modules\Blog\Actions\CreateTag;
use Modules\Blog\Actions\DeleteTag;
use Modules\Blog\Actions\ListAdminTags;
use Modules\Blog\Actions\UpdateTag;
use App\Http\Controllers\Controller;
use Modules\Blog\Models\Tag;

class TagController extends Controller
{
    public function index(ListAdminTags $action)
    {
        $items = $action->handle(request('q') ? request('q') : '');

        return view('blog::admin.tags.index', ['items' => $items]);
    }

    public function create()
    {
        return view('blog::admin.tags.create');
    }

    public function store(CreateTag $action)
    {
        $action->handle(request()->all());

        return redirect()->route('admin.tags.index')->with('status', 'Tag criada.');
    }

    public function edit(Tag $tag)
    {
        return view('blog::admin.tags.edit', ['item' => $tag]);
    }

    public function update(Tag $tag, UpdateTag $action)
    {
        $action->handle($tag, request()->all());

        return redirect()->route('admin.tags.index')->with('status', 'Tag atualizada.');
    }

    public function destroy(Tag $tag, DeleteTag $action)
    {
        $action->handle($tag);

        return redirect()->route('admin.tags.index')->with('status', 'Tag removida.');
    }
}
