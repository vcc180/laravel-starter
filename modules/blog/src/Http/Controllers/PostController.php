<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Models\Post;

class PostController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $items = Post::query()->orderByDesc('id')->paginate(20);

        return view('blog::admin.index', ['items' => $items]);
    }

    public function create()
    {
        return view('blog::admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug'],
            'body' => ['nullable', 'string'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:blog_tags,id'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $post = Post::create($data);
        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

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

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug,' . $post->id],
            'body' => ['nullable', 'string'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:blog_tags,id'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $post->update($data);
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.blog.index')->with('status', 'Post atualizado.');
    }

    public function destroy(Post $post)
    {
        $post->tags()->detach();
        $post->delete();

        return back()->with('status', 'Post removido.');
    }
}
