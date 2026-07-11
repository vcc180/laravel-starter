<?php

namespace Core\Managers;

use Core\Contracts\ThemeInterface;
use Core\Contracts\ResultInterface;
use Core\Contracts\RegistryInterface;
use Core\Exceptions\PackageException;
use Core\Support\Manifest;
use Core\Support\Result;

final class ThemeManager extends PackageManager
{
    public function supports(string $path): bool
    {
        if (!is_file($path)) {
            return false;
        }

        $parts = explode('/', str_replace(['\\', '/'], '/', $path));
        $filename = end($parts);

        return str_starts_with($filename, 'theme-');
    }

    public function register(string $name, array $config = []): ResultInterface
    {
        $path = $this->resolveThemePath($name, $config);

        if (!$path || !is_file($path.'/theme.json')) {
            return new Result(false, 'Manifesto do tema não encontrado.', null);
        }

        $manifest = new Manifest($path.'/theme.json', $path);

        if (!$manifest->isValid()) {
            return new Result(false, 'Manifesto inválido.', null);
        }

        $this->packageRegistry->register($manifest->getSlug(), [
            'manifest' => $manifest,
            'status' => $this->packageRegistry->has($manifest->getSlug()) ? 'upgraded' : 'discovered',
        ]);

        return Result::ok()->withManifest($manifest);
    }

    public function load(string $slug): ThemeInterface
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            throw new PackageException("Tema não registrado: {$slug}");
        }

        if (isset($pkg['instance'])) {
            return $pkg['instance'];
        }

        $provider = $this->resolveThemeClass($slug);

        $this->packageRegistry->register($slug, [
            'manifest' => $pkg['manifest'],
            'status' => 'loaded',
            'instance' => $provider,
        ]);

        if ($this->registry !== null) {
            $this->registry->put('theme.'.$slug, $provider);
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

        if ($provider instanceof ThemeInterface) {
            $provider->shutdown();
        } elseif ($provider !== null && method_exists($provider, 'shutdown')) {
            $provider::shutdown();
        }
    }

    private function resolveThemePath(string $name, array $config = []): ?string
    {
        $base = $config['path'] ?? ($this->directories['themes'] ?? base_path('themes'));

        if (is_dir(rtrim($base, '/').'/'.$name)) {
            return rtrim($base, '/').'/'.$name;
        }

        return null;
    }

    private function resolveThemeClass(string $slug): ThemeInterface
    {
        $pkg = $this->packageRegistry->get($slug);

        if ($pkg === null) {
            throw new PackageException("Tema não registrado: {$slug}");
        }

        $provider = $pkg['manifest']->get('provider', '');

        if ($provider === '') {
            return new class implements ThemeInterface {
                public function register(): void {}
                public function boot(): void {}
                public function shutdown(): void {}
                public function name(): string { return 'default'; }
                public function path(): string { return base_path('themes/default'); }
            };
        }

        $base = $pkg['manifest']->getBasePath();
        $candidate = $base.'/'.str_replace(['/', '\\'], '/', ltrim($provider, '\\')).'.php';

        if (!is_file($candidate)) {
            throw new PackageException("Provider não encontrado para tema {$slug}: {$provider}");
        }

        require_once $candidate;

        if (!class_exists($provider)) {
            throw new PackageException("Classe provider não encontrada: {$provider}");
        }

        $instance = new $provider();

        if (!($instance instanceof ThemeInterface)) {
            throw new PackageException("Provider do tema deve implementar ThemeInterface: {$provider}");
        }

        return $instance;
    }
}
