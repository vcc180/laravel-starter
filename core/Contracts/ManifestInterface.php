<?php

namespace Core\Contracts;

use Core\Exceptions\CoreException;

interface ManifestInterface
{
    public function toArray(): array;

    /**
     * @throws CoreException
     */
    public function require(array $keys): void;

    public function get(string $key, mixed $default = null): mixed;
}
