<?php

namespace Core\Managers;

use Core\Contracts\ManifestInterface;
use Core\Contracts\RegistryInterface;
use Core\Contracts\ResultInterface;
use Core\Contracts\PackageInterface;
use Core\Contracts\ModuleInterface;
use Core\Contracts\PluginInterface;
use Core\Contracts\ThemeInterface;
use Core\Contracts\ManagerInterface;
use Core\Contracts\HookInterface;
use Core\Exceptions\PackageException;
use Core\Exceptions\HookException;
use Core\Registry\PackageRegistry;
use Core\Discovery\Discovery;
use Core\Support\Manifest;
use InvalidArgumentException;
use RuntimeException;

/**
 * Manager base de pacotes. Respira discovery -> manifest -> registry -> resolve -> boot por tipo.
 */
abstract class PackageManager
{
    use \Core\Managers\Concerns\Bootable;

    /**
     * @var RegistryInterface|null
     */
    protected ?RegistryInterface $registry;

    /**
     * @var PackageRegistry
     */
    protected PackageRegistry $packageRegistry;

    /**
     * @var Discovery
     */
    protected Discovery $discovery;

    /**
     * @var array<string, string>
     */
    protected array $directories;

    /**
     * @param RegistryInterface|null $registry
     * @param array<string, string>  $directories
     */
    public function __construct(?RegistryInterface $registry = null, array $directories = [])
    {
        $this->registry = $registry;
        $this->packageRegistry = new PackageRegistry();
        $this->discovery = new Discovery($directories);
        $this->directories = $directories;
    }

    /**
     * @return array<string, string>
     */
    public function directories(): array
    {
        return $this->directories;
    }

    public function packageRegistry(): PackageRegistry
    {
        return $this->packageRegistry;
    }

    public function boot(): ResultInterface
    {
        if ($this->isBooted()) {
            return \Core\Support\Result::ok();
        }

        try {
            $manifests = $this->loadManifests();
            $packages = $this->buildPackagesArray($manifests);
            $order = (new \Core\Support\DependencyResolver())->resolve($packages);

            foreach ($order as $slug) {
                if (!$this->packageRegistry->has($slug)) {
                    continue;
                }
                $this->bootPackage($slug);
            }

            $this->markBooted();

            return \Core\Support\Result::ok();
        } catch (\Core\Exceptions\HookException|InvalidArgumentException|RuntimeException $e) {
            return \Core\Support\Result::fail($e->getMessage());
        }
    }

    public function shutdown(): ResultInterface
    {
        if (!$this->isBooted()) {
            return \Core\Support\Result::ok();
        }

        foreach (array_reverse(array_keys($this->packageRegistry->all())) as $slug) {
            $this->shutdownPackage($slug);
        }

        $this->packageRegistry = new \Core\Registry\PackageRegistry();
        $this->markBooted(false);

        return \Core\Support\Result::ok();
    }

    /**
     * @return array<string, Manifest>
     */
    protected function loadManifests(): array
    {
        $results = [];
        $scan = $this->discovery->scan($this->directories);

        foreach ($scan as $type => $paths) {
            foreach ($paths as $path) {
                $manifest = new Manifest($path, $this->directories[$type] ?? $type);

                if (!$manifest->isValid()) {
                    continue;
                }

                $slug = $manifest->getSlug();
                $results[$slug] = $manifest;
            }
        }

        return $results;
    }

    /**
     * @param array<string, Manifest> $manifests
     * @return array<string, array{slug:string, version:string, requires:array<string, string>}>
     */
    protected function buildPackagesArray(array $manifests): array
    {
        $packages = [];

        foreach ($manifests as $manifest) {
            $packages[$manifest->getSlug()] = [
                'slug' => $manifest->getSlug(),
                'version' => $manifest->getVersion() ?? '1.0.0',
                'requires' => $manifest->get('requires', []),
            ];
        }

        return $packages;
    }

    /**
     * Verifica e registra pacote via Registry global quando habilitado.
     */
    protected function registerInGlobalRegistry(string $slug, object $instance): void
    {
        if ($this->registry === null) {
            return;
        }

        $this->registry->put($slug, $instance);
    }

    abstract protected function bootPackage(string $slug): void;

    abstract protected function shutdownPackage(string $slug): void;
}
