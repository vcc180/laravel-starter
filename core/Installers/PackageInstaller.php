<?php

namespace Core\Installers;

use InvalidArgumentException;

final class PackageInstaller
{
    /**
     * @param array<string, InstallerInterface> $installers
     */
    public function __construct(private array $installers) {}

    public function install(string $type, string $name, array $config = []): \Core\Contracts\ResultInterface
    {
        if (!isset($this->installers[$type])) {
            return \Core\Support\Result::fail('Tipo de pacote não suportado: '.$type);
        }

        return $this->installers[$type]->install($name, $config);
    }

    /**
     * @return array<string, InstallerInterface>
     */
    public function types(): array
    {
        return $this->installers;
    }
}
