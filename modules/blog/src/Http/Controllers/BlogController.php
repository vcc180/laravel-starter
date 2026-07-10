<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Models\Post;

class BlogController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $items = Post::query()
            ->where('is_published', true)
            ->when(request('q'), function ($query, $q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(10);

        return view('blog::public.index', ['posts' => $items]);
    }

    public function show(Post $post)
    {
        return view('blog::public.show', ['post' => $post]);
    }
}
