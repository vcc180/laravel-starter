<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadViewsFrom(base_path('modules/blog/src/resources/views'), 'blog');
    }

    public function boot(): void
    {
        //
    }
}
