<?php

namespace Core\Managers\Concerns;

/**
 * Trait reutilizável para managers com estado de boot.
 */
trait Bootable
{
    /**
     * @var bool
     */
    private bool $booted = false;

    protected function markBooted(): void
    {
        $this->booted = true;
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }
}
