<?php

namespace Core\Registry;

use Core\Contracts\RegistryInterface;

final class Registry implements RegistryInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $store = [];

    public function register(string $type, string $key, mixed $value): void
    {
        $this->store[$this->storageKey($type, $key)] = $value;
    }

    /**
     * @return array<int, mixed>
     */
    public function all(string $type): array
    {
        $prefix = $type.'.';
        $values = [];

        foreach ($this->store as $k => $v) {
            if (str_starts_with($k, $prefix)) {
                $values[] = $v;
            }
        }

        return $values;
    }

    public function get(string $type, string $key, mixed $default = null): mixed
    {
        return $this->store[$this->storageKey($type, $key)] ?? $default;
    }

    public function has(string $type, string $key): bool
    {
        return array_key_exists($this->storageKey($type, $key), $this->store);
    }

    public function remove(string $type, string $key): void
    {
        unset($this->store[$this->storageKey($type, $key)]);
    }

    public function clear(string $type): void
    {
        if ($type === '') {
            $this->store = [];

            return;
        }

        $prefix = $type.'.';
        $keys = array_keys($this->store);

        foreach ($keys as $k) {
            if (str_starts_with($k, $prefix)) {
                unset($this->store[$k]);
            }
        }
    }

    private function storageKey(string $type, string $key): string
    {
        return $type.'.'.$key;
    }
}
