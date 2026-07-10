<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BlogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadViewsFrom(base_path('modules/blog/src/resources/views'), 'blog');

        $hooks = $this->app->make(\Core\Contracts\HookInterface::class);

        $hooks->addAction('blog.post.created', function ($post) {
            Log::info('Post criado.', ['post_id' => $post->id]);
        });

        $hooks->addAction('blog.post.deleted', function ($post) {
            Log::info('Post removido.', ['post_id' => $post->id]);
        });

        $hooks->addAction('blog.category.updated', function ($category) {
            Log::info('Categoria atualizada.', ['category_id' => $category->id]);
        });

        $hooks->addAction('blog.tag.deleted', function ($tag) {
            Log::info('Tag removida.', ['tag_id' => $tag->id]);
        });
    }
}
