<?php

namespace Themes\Institutional;

use Core\Contracts\ThemeInterface;

final class InstitutionalServiceProvider implements ThemeInterface
{
    public function register(): void
    {
    }

    public function boot(): void
    {
    }

    public function shutdown(): void
    {
    }

    public function name(): string
    {
        return 'Institucional';
    }

    public function slug(): string
    {
        return 'institutional';
    }

    public function path(): string
    {
        return base_path('themes/institutional');
    }
}
