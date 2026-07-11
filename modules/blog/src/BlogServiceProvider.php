<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Contracts\HookInterface;
use Modules\Blog\Actions\GetBlogStats;
use Modules\Blog\Listeners\PostCreatedListener;
use Modules\Blog\Listeners\PostDeletedListener;

class BlogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadViewsFrom(base_path('modules/blog/src/resources/views'), 'blog');
    }

    public function boot(): void
    {
        if (!$this->app->bound(HookInterface::class)) {
            return;
        }

        $hooks = $this->app->make(HookInterface::class);

        $hooks->addAction('blog.post.created', function ($post) {
            app(\Modules\Blog\Listeners\PostCreatedListener::class)->handle($post);
        }, 5);

        $hooks->addAction('blog.post.created', function ($post) {
            app(\Modules\Blog\Listeners\PostCreatedListener::class)->handle($post);
        }, 10);

        $hooks->addAction('blog.post.deleted', function ($post) {
            app(\Modules\Blog\Listeners\PostDeletedListener::class)->handle($post);
        }, 1);

        $hooks->addAction('blog.category.created', function (): void {
            app(\Modules\Blog\Actions\GetBlogStats::class)->handle();
        });
        $hooks->addAction('blog.category.deleted', function (): void {
            app(\Modules\Blog\Actions\GetBlogStats::class)->handle();
        });
        $hooks->addAction('blog.tag.created', function (): void {
            app(\Modules\Blog\Actions\GetBlogStats::class)->handle();
        });
        $hooks->addAction('blog.tag.deleted', function (): void {
            app(\Modules\Blog\Actions\GetBlogStats::class)->handle();
        });
    }
}
