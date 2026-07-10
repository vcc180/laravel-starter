<?php

namespace Core\Contracts;

interface ManagerInterface
{
    public function register(string $type, string $key, mixed $instance): void;

    /**
     * @return array<string, mixed>
     */
    public function all(string $type): array;

    public function get(string $type, string $key, mixed $default = null): mixed;

    public function has(string $type, string $key): bool;

    public function remove(string $type, string $key): void;

    public function clear(string $type): void;
}
