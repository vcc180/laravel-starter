<?php

namespace Core\Support;

use Core\Contracts\ResultInterface;
use InvalidArgumentException;

final class Manifest
{
    private array $data;

    public function __construct(string $path)
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new InvalidArgumentException("Manifest file not found or unreadable: {$path}");
        }

        $content = file_get_contents($path);
        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw new InvalidArgumentException("Invalid JSON in manifest: {$path}");
        }

        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function require(array $keys): void
    {
        $missing = [];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->data)) {
                $missing[] = $key;
            }
        }

        if ($missing !== []) {
            throw new InvalidArgumentException('Missing required manifest keys: '.implode(', ', $missing));
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
}
