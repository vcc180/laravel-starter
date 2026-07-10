<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Managers\ModuleManager;
use Core\Managers\PluginManager;
use Core\Managers\ThemeManager;
use Modules\Blog\Providers\BlogServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Managers do Core.
     *
     * @var array<string, ModuleManager|PluginManager|ThemeManager>
     */
    private array $managers = [];

    public function register(): void
    {
        $registry = null;

        if (class_exists(\Core\Registry\Registry::class)) {
            $registry = new \Core\Registry\Registry();
        }

        $this->managers = [
            'modules' => new ModuleManager(
                $registry,
                ['modules' => base_path('modules')]
            ),
            'plugins' => new PluginManager(
                $registry,
                ['plugins' => base_path('plugins')]
            ),
            'themes' => new ThemeManager(
                $registry,
                ['themes' => base_path('themes')]
            ),
        ];

        $this->app->singleton(\Core\Contracts\HookInterface::class, \Core\Hooks\HookManager::class);
    }

    public function boot(): void
    {
        foreach ($this->managers as $manager) {
            $result = $manager->boot();

            if (!$result->ok()) {
                // Em produção, logar erro.
            }
        }
    }
}
