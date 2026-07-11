<?php

namespace Modules\Blog\Actions;

use Illuminate\Support\Facades\Cache;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Tag;

final class GetBlogStats
{
    public function handle(): array
    {
        return Cache::remember('blog.stats', now()->addMinutes(5), function (): array {
            $total = Post::count();
            $published = Post::where('is_published', true)->count();
            $draft = $total - $published;

            return [
                'posts' => $total,
                'published' => $published,
                'draft' => $draft,
                'categories' => Category::count(),
                'tags' => Tag::count(),
                'last_post_at' => Post::max('created_at'),
            ];
        });
    }
}
