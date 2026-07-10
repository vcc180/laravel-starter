<?php

namespace Core\Contracts;

/**
 * @method string slug()
 * @method array dependencies()
 * @method void register(): void
 * @method void boot(): void
 */
interface PluginInterface
{
    public function slug(): string;

    public function dependencies(): array;

    public function register(): void;

    public function boot(): void;
}
