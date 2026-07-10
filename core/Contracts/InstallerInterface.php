<?php

namespace Core\Contracts;

/**
 * Install result contract so the UI/CLI can react to structured outcomes.
 */
interface InstallerInterface
{
    /**
     * @return ResultInterface
     */
    public function install(string $type, string $slug, array $options = []);

    /**
     * @return ResultInterface
     */
    public function uninstall(string $type, string $slug);

    /**
     * @return ResultInterface
     */
    public function upgrade(string $type, string $slug, string $targetVersion);
}
