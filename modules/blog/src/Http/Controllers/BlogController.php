<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Actions\GetPublishedPost;
use Modules\Blog\Actions\ListPublishedPosts;
use Modules\Blog\Models\Post;

class BlogController extends \App\Http\Controllers\Controller
{
    public function index(ListPublishedPosts $action, Request $request)
    {
        $posts = $action->handle($request->string('q')->__toString(), 10);

        return view('blog::public.index', ['posts' => $posts]);
    }

    public function show(Post $post, GetPublishedPost $action)
    {
        $post = $action->handle($post);

        return view('blog::public.show', ['post' => $post]);
    }
}
