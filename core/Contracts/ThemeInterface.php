<?php

namespace Core\Contracts;

interface ThemeInterface
{
    public function name(): string;

    public function slug(): string;

    public function parent(): ?string;

    public function path(): string;

    public function assetPath(): string;

    public function viewNamespaces(): array;
}
