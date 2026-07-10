<?php

namespace Core\Managers;

use Core\Contracts\ModuleInterface;
use Core\Contracts\HookInterface;
use Core\Contracts\ResultInterface;
use Core\Contracts\RegistryInterface;
use Core\Contracts\ProviderInterface;
use Core\Exceptions\PackageException;
use Core\Support\Result;
use InvalidArgumentException;

final class ModuleManager extends PackageManager
{
    public function supports(string $path): bool
    {
        if (!is_file($path)) {
            return false;
        }

        $manifest = new Manifest($path, dirname($path));

        return $manifest->isValid() && $manifest->get('type') === 'module';
    }

    public function register(string $name, array $config = []): ResultInterface
    {
        $path = $this->resolveModulePath($name, $config);

        if (!$path || !is_file($path.'/module.json')) {
            return new Result(false, 'Manifesto do módulo não encontrado.', null);
        }

        $manifest = new Manifest($path.'/module.json', $path);

        if (!$manifest->isValid()) {
            return new Result(false, 'Manifesto inválido.', null);
        }

        $this->packageRegistry->register($manifest->getSlug(), [
            'manifest' => $manifest,
            'status' => $this->packageRegistry->has($manifest->getSlug()) ? 'upgraded' : 'discovered',
        ]);

        return new Result(true, 'Módulo registrado.', $manifest);
    }

    public function load(string $slug): ModuleInterface
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            throw new PackageException("Módulo não registrado: {$slug}");
        }

        if (isset($pkg['instance'])) {
            return $pkg['instance'];
        }

        $provider = $this->resolveModuleProvider($slug);

        if ($provider instanceof ProviderInterface) {
            $provider->register();
        } elseif ($provider !== null) {
            $provider::register();
        }

        $this->registerRoutes($provider, $slug);
        $this->registerViews($provider, $slug);
        $this->registerMigrations($provider, $slug);

        $this->packageRegistry->register($slug, [
            'manifest' => $pkg['manifest'],
            'status' => 'loaded',
            'instance' => $provider,
        ]);

        if ($this->registry !== null) {
            $this->registry->put('module.'.$slug, $provider);
        }

        return $provider;
    }

    public function reload(string $slug): ResultInterface
    {
        $this->packageRegistry->remove($slug);

        return $this->register($slug);
    }

    public function update(string $slug, array $newConfig = []): ResultInterface
    {
        $this->reload($slug);

        return parent::boot();
    }

    protected function bootPackage(string $slug): void
    {
        $this->load($slug);
    }

    protected function shutdownPackage(string $slug): void
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            return;
        }

        $provider = $pkg['instance'] ?? null;

        if ($provider instanceof ProviderInterface) {
            $provider->shutdown();
        } elseif ($provider !== null && method_exists($provider, 'shutdown')) {
            $provider::shutdown();
        }
    }

    private function resolveModulePath(string $name, array $config = []): ?string
    {
        $base = $config['path'] ?? ($this->directories['modules'] ?? base_path('modules'));

        if (is_dir(rtrim($base, '/').'/'.$name)) {
            return rtrim($base, '/').'/'.$name;
        }

        return null;
    }

    private function resolveModuleProvider(string $slug): ?object
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            throw new PackageException("Módulo não registrado: {$slug}");
        }

        $provider = $pkg['manifest']->get('provider', '');

        if ($provider === '') {
            return null;
        }

        $base = $pkg['manifest']->getBasePath();
        $candidate = $base.'/'.str_replace(['/', '\\'], '/', ltrim($provider, '\\')).'.php';

        if (!is_file($candidate)) {
            throw new PackageException("Provider não encontrado para módulo {$slug}: {$provider}");
        }

        require_once $candidate;

        if (!class_exists($provider)) {
            throw new PackageException("Classe de provider não encontrada: {$provider}");
        }

        return new $provider();
    }

    private function registerRoutes(object $provider, string $slug): void
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            return;
        }

        $routes = $pkg['manifest']->get('routes', ['web' => 'routes/web.php']);

        foreach ($routes as $guard => $file) {
            $path = $pkg['manifest']->getBasePath().'/'.$file;

            if (!is_file($path)) {
                continue;
            }

            if ($guard === 'api' && method_exists($provider, 'mapApiRoutes')) {
                $provider->mapApiRoutes();
            } elseif (method_exists($provider, 'mapWebRoutes')) {
                $provider->mapWebRoutes();
            }
        }
    }

    private function registerViews(object $provider, string $slug): void
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            return;
        }

        $views = $pkg['manifest']->get('views', 'resources/views');

        if (is_dir($pkg['manifest']->getBasePath().'/'.$views)) {
            if (method_exists($provider, 'loadViewsFrom')) {
                $provider->loadViewsFrom($pkg['manifest']->getBasePath().'/'.$views, $slug);
            }
        }
    }

    private function registerMigrations(object $provider, string $slug): void
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            return;
        }

        $migrations = $pkg['manifest']->get('migrations');

        if (!is_array($migrations) || $migrations === []) {
            return;
        }

        foreach ($migrations as $file) {
            $path = $pkg['manifest']->getBasePath().'/'.$file;

            if (is_file($path)) {
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--path' => $path,
                    '--force' => true,
                ]);
            }
        }
    }
}
