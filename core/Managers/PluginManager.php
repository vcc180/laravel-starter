<?php

namespace Core\Managers;

use Core\Contracts\PluginInterface;
use Core\Contracts\ResultInterface;
use Core\Contracts\RegistryInterface;
use Core\Exceptions\PackageException;
use Core\Support\Manifest;
use Core\Support\Result;

final class PluginManager extends PackageManager
{
    public function supports(string $path): bool
    {
        if (!is_file($path)) {
            return false;
        }

        $parts = explode('/', str_replace(['\\', '/'], '/', $path));
        $filename = end($parts);

        return str_ends_with($filename, 'Plugin.php');
    }

    public function register(string $name, array $config = []): ResultInterface
    {
        $path = $this->resolvePluginPath($name, $config);

        if (!$path || !is_file($path.'/plugin.json')) {
            return new Result(false, 'Manifesto do plugin não encontrado.', null);
        }

        $manifest = new Manifest($path.'/plugin.json', $path);

        if (!$manifest->isValid()) {
            return new Result(false, 'Manifesto inválido.', null);
        }

        $this->packageRegistry->register($manifest->getSlug(), [
            'manifest' => $manifest,
            'status' => $this->packageRegistry->has($manifest->getSlug()) ? 'upgraded' : 'discovered',
        ]);

        return Result::ok()->withManifest($manifest);
    }

    public function load(string $slug): PluginInterface
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            throw new PackageException("Plugin não registrado: {$slug}");
        }

        if (isset($pkg['instance'])) {
            return $pkg['instance'];
        }

        $provider = $this->resolvePluginProvider($slug, $pkg['manifest']);
        $this->registerViews($provider, $slug);

        $this->packageRegistry->register($slug, [
            'manifest' => $pkg['manifest'],
            'status' => 'loaded',
            'instance' => $provider,
        ]);

        if ($this->registry !== null) {
            $this->registry->put('plugin.'.$slug, $provider);
        }

        return $provider;
    }

    public function bootPackage(string $slug): void
    {
        $this->load($slug);
    }

    public function shutdownPackage(string $slug): void
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            return;
        }

        $provider = $pkg['instance'] ?? null;

        if ($provider instanceof PluginInterface) {
            $provider->shutdown();
        } elseif ($provider !== null && method_exists($provider, 'shutdown')) {
            $provider::shutdown();
        }
    }

    private function resolvePluginPath(string $name, array $config = []): ?string
    {
        $base = $config['path'] ?? ($this->directories['plugins'] ?? base_path('plugins'));

        if (is_dir(rtrim($base, '/').'/'.$name)) {
            return rtrim($base, '/').'/'.$name;
        }

        return null;
    }

    private function resolvePluginProvider(string $slug, Manifest $manifest): object
    {
        $provider = $manifest->get('provider', '');

        if ($provider === '') {
            return new class implements PluginInterface {
                public function register(): void {}
                public function boot(): void {}
                public function shutdown(): void {}
            };
        }

        $base = $manifest->getBasePath();
        $candidate = $base.'/'.str_replace(['/', '\\'], '/', ltrim($provider, '\\')).'.php';

        if (!is_file($candidate)) {
            throw new PackageException("Provider não encontrado para plugin {$slug}: {$provider}");
        }

        require_once $candidate;

        if (!class_exists($provider)) {
            throw new PackageException("Classe provider não encontrada: {$provider}");
        }

        $instance = new $provider();

        if (!($instance instanceof PluginInterface)) {
            throw new PackageException("Provider do plugin deve implementar PluginInterface: {$provider}");
        }

        return $instance;
    }

    private function registerViews(object $provider, string $slug): void
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            return;
        }

        $views = $pkg['manifest']->get('views', 'resources/views');
        $base = $pkg['manifest']->getBasePath().'/'.$views;

        if (is_dir($base) && method_exists($provider, 'loadViewsFrom')) {
            $provider->loadViewsFrom($base, $slug);
        }
    }
}
