<?php

namespace Modules\Blog\Listeners;

use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Tag;

class PostCreatedListener
{
    public function handle(Post $post): void
    {
        if (!($post->blog_category_id ?? null)) {
            $default = Category::firstOrCreate(['slug' => 'default'], ['name' => 'Default']);
            $post->update(['blog_category_id' => $default->id]);
        }

        $tag = Tag::firstOrCreate(['slug' => 'novo'], ['name' => 'Novo']);
        $post->tags()->syncWithoutDetaching([$tag->id]);
    }
}
