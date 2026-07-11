<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Core\Contracts\ThemeInterface;
use Core\Managers\ThemeManager;
use Core\Managers\ModuleManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // App-specific bindings, if needed.
    }

    public function boot(ModuleManager $modules, ThemeManager $themes): void
    {
        $this->shareActiveTheme($themes);
        $this->shareAdminSidebar($modules);
    }

    private function shareActiveTheme(ThemeManager $themes): void
    {
        $active = config('app.theme', env('APP_THEME'));

        if (!$active || !class_exists(ThemeInterface::class)) {
            return;
        }

        try {
            $themes->boot();
            $theme = $themes->load($active);
        } catch (\Throwable $e) {
            return;
        }

        $base = rtrim($theme->path(), '/');

        if (is_dir($base.'/resources/views')) {
            $this->loadViewsFrom($base.'/resources/views', 'theme');
        }

        if (is_dir($base.'/public')) {
            $this->publishes([
                $base.'/public' => public_path('theme'),
            ], 'public');

            if (is_dir(public_path('theme'))) {
                $this->app->booted(function () use ($base) {
                    if (is_file(public_path('theme/css/theme.css'))) {
                        View::share('themeCss', asset('theme/css/theme.css'));
                    }
                    if (is_file(public_path('theme/js/theme.js'))) {
                        View::share('themeJs', asset('theme/js/theme.js'));
                    }
                });
            }
        }

        if (is_file($base.'/resources/views/layouts/public.blade.php')) {
            View::share('themeLayout', 'theme::layouts.public');
        }
    }

    private function shareAdminSidebar(ModuleManager $modules): void
    {
        logger()->info('sidebar_build_start');
        $menu = [];

        $fixed = [
            ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'bi bi-speedometer2'],
            ['name' => 'Repositório', 'route' => 'admin.repository.index', 'icon' => 'bi bi-box'],
        ];

        foreach ($fixed as $item) {
            if (\Illuminate\Support\Facades\Route::has($item['route'])) {
                $menu[] = $item;
            }
        }

        // Acesso manual garantido para módulos principais enquanto o discovery automático é validado
        $manual = [
            ['name' => 'Blog', 'route' => 'admin.blog.index', 'icon' => 'bi bi-file-earmark-text'],
            ['name' => 'Categorias', 'route' => 'admin.categories.index', 'icon' => 'bi bi-folder2-open'],
        ];

        foreach ($manual as $item) {
            if (\Illuminate\Support\Facades\Route::has($item['route'])) {
                $menu[] = $item;
            }
        }

        try {
            $modules->boot();

            if ($modules->packageRegistry()->all() === []) {
                logger()->info('sidebar_discovery_fallback');
                $discovery = new \Core\Discovery\Discovery(['modules' => base_path('modules')]);
                $scanned = $discovery->scan(['modules' => base_path('modules')])['modules'] ?? [];
                logger()->info('sidebar_discovery_scanned', ['count' => count($scanned)]);
                foreach ($scanned as $path) {
                    try {
                        $result = $modules->register(basename(dirname($path)));
                        logger()->info('sidebar_discovery_register', ['path' => $path, 'result' => $result->isOk(), 'message' => $result->message()]);
                    } catch (\Throwable $e) {
                        logger()->warning('sidebar_register_failed', ['path' => $path, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                    }
                }
                $modules->boot();
            }

            logger()->info('sidebar_registry_pre', ['size' => count($modules->packageRegistry()->all())]);
            foreach ($modules->packageRegistry()->all() as $slug => $pkg) {
                $name = $pkg['manifest']->get('name', '') ?: $slug;
                $routeName = 'admin.'.$slug.'.index';

                if (\Illuminate\Support\Facades\Route::has($routeName)) {
                    $menu[] = [
                        'name' => $name,
                        'route' => $routeName,
                    ];
                }
            }
        } catch (\Throwable $e) {
            logger()->warning('sidebar_build_failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }

        logger()->info('sidebar_build_done', ['size' => count($menu)]);
        View::share('adminSidebarMenu', $menu);
    }
}
