<?php

namespace Core\Contracts;

interface PackageInterface
{
    public function name(): string;

    public function slug(): string;

    public function version(): string;

    public function type(): string;

    public function path(): string;

    public function provider(): string;

    public function providers(): array;

    public function requires(): array;

    public function manifest(): ManifestInterface;

    public function isBooted(): bool;

    public function boot(): void;

    public function isActive(): bool;
}
