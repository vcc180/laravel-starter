<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Actions\CreatePost;
use Modules\Blog\Actions\DeletePost;
use Modules\Blog\Actions\UpdatePost;
use Modules\Blog\Http\Requests\Admin\PostRequest;
use Modules\Blog\Models\Post;

class PostController extends \App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        return view('blog::admin.index', ['items' => Post::query()->orderByDesc('id')->paginate(20)]);
    }

    public function create()
    {
        return view('blog::admin.create');
    }

    public function store(PostRequest $request, CreatePost $action)
    {
        $action->handle($request->validated());

        return redirect()->route('admin.blog.index')->with('status', 'Post criado.');
    }

    public function show(Post $post)
    {
        return view('blog::admin.show', ['item' => $post]);
    }

    public function edit(Post $post)
    {
        return view('blog::admin.edit', ['item' => $post]);
    }

    public function update(PostRequest $request, Post $post, UpdatePost $action)
    {
        $action->handle($post, $request->validated());

        return redirect()->route('admin.blog.index')->with('status', 'Post atualizado.');
    }

    public function destroy(Post $post, DeletePost $action)
    {
        $action->handle($post);

        return back()->with('status', 'Post removido.');
    }
}
