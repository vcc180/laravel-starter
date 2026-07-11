<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Registry\Registry;
use Core\Contracts\RegistryInterface;
use Core\Contracts\HookInterface;
use Core\Hooks\HookManager;
use Core\Managers\ModuleManager;
use Core\Managers\PluginManager;
use Core\Managers\ThemeManager;
use Core\Installers\PackageInstaller;
use Core\Installers\ModuleInstaller;
use Core\Installers\PluginInstaller;
use Core\Installers\ThemeInstaller;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, ModuleManager|PluginManager|ThemeManager>
     */
    private array $managers = [];

    public function register(): void
    {
        $this->app->singleton(RegistryInterface::class, function () {
            return new Registry();
        });

        $this->app->singleton(HookInterface::class, HookManager::class);

        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager(
                $app->make(RegistryInterface::class),
                ['modules' => base_path('modules')]
            );
        });

        $this->app->singleton(PluginManager::class, function ($app) {
            return new PluginManager(
                $app->make(RegistryInterface::class),
                ['plugins' => base_path('plugins')]
            );
        });

        $this->app->singleton(ThemeManager::class, function ($app) {
            return new ThemeManager(
                $app->make(RegistryInterface::class),
                ['themes' => base_path('themes')]
            );
        });

        $this->managers = [
            'modules' => $this->app->make(ModuleManager::class),
            'plugins' => $this->app->make(PluginManager::class),
            'themes' => $this->app->make(ThemeManager::class),
        ];

        $this->app->singleton(PackageInstaller::class, function ($app) {
            return new PackageInstaller([
                'module' => new ModuleInstaller(
                    $app->make(ModuleManager::class),
                    $app->make(RegistryInterface::class),
                    $app->make(HookInterface::class),
                ),
                'plugin' => new PluginInstaller(
                    $app->make(PluginManager::class),
                    $app->make(RegistryInterface::class),
                    $app->make(HookInterface::class),
                ),
                'theme' => new ThemeInstaller(
                    $app->make(ThemeManager::class),
                    $app->make(RegistryInterface::class),
                    $app->make(HookInterface::class),
                ),
            ]);
        });
    }

    public function boot(): void
    {
        foreach ($this->managers as $manager) {
            $manager->boot();
        }
    }
}
