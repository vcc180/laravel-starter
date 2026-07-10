<?php

namespace Core\Registry;

/**
 * Registry especializado para pacotes do Core (modules, plugins, themes).
 */
final class PackageRegistry
{
    /**
     * @var array<string, array{
     *   manifest: Core\Contracts\ManifestInterface,
     *   status: string,
     *   instance?: object,
     * }>
     */
    private array $packages = [];

    public function register(string $slug, array $payload): void
    {
        $this->packages[$slug] = $payload;
    }

    public function has(string $slug): bool
    {
        return array_key_exists($slug, $this->packages);
    }

    /**
     * @return array<string, array{
     *   manifest: Core\Contracts\ManifestInterface,
     *   status: string,
     *   instance?: object,
     * }>
     */
    public function all(): array
    {
        return $this->packages;
    }

    /**
     * @return array{
     *   manifest: Core\Contracts\ManifestInterface,
     *   status: string,
     *   instance?: object,
     * }|null
     */
    public function get(string $slug): ?array
    {
        return $this->packages[$slug] ?? null;
    }

    public function remove(string $slug): void
    {
        unset($this->packages[$slug]);
    }

    /**
     * @return array<string, Core\Contracts\ManifestInterface>
     */
    public function manifests(): array
    {
        $out = [];

        foreach ($this->packages as $slug => $payload) {
            $out[$slug] = $payload['manifest'];
        }

        return $out;
    }
}
