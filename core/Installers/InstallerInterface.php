<?php

namespace Core\Installers;

use Core\Contracts\ResultInterface;

interface InstallerInterface
{
    public function install(string $name, array $config = []): ResultInterface;

    public function uninstall(string $slug): ResultInterface;
}
