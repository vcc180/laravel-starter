<?php

namespace Core\Support;

use Core\Exceptions\CoreException;

final class DependencyResolver
{
    /**
     * @param array<string, array{slug:string,version:string,requires:array}> $packages
     * @return array<int, string>
     */
    public function resolve(array $packages): array
    {
        $graph = [];
        $slugs = [];

        foreach ($packages as $slug => $pkg) {
            $graph[$slug] = $pkg['requires'] ?? [];
            $slugs[$slug] = true;
        }

        $order = [];
        $visited = [];

        foreach (array_keys($slugs) as $slug) {
            if (!isset($visited[$slug])) {
                $this->walk($slug, $graph, $visited, $order, $slugs);
            }
        }

        return $order;
    }

    /**
     * @param array<string, array<string, string>> $graph
     *
     * @throws CoreException
     */
    private function walk(string $slug, array $graph, array &$visited, array &$order, array $slugs): void
    {
        if (isset($visited[$slug]) && $visited[$slug] === 'gray') {
            throw new CoreException("Circular dependency detected: {$slug}");
        }

        if (isset($visited[$slug]) && $visited[$slug] === 'black') {
            return;
        }

        $visited[$slug] = 'gray';

        foreach ($graph[$slug] ?? [] as $dep => $constraint) {
            if (!isset($slugs[$dep])) {
                throw new CoreException("Missing dependency: {$dep}");
            }

            $this->walk($dep, $graph, $visited, $order, $slugs);
        }

        $visited[$slug] = 'black';
        $order[] = $slug;
    }
}
