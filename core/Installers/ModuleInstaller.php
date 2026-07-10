<?php

namespace Core\Installers;

use App\Models\InstalledPackage;
use Core\Contracts\HookInterface;
use Core\Contracts\RegistryInterface;
use Core\Contracts\ResultInterface;
use Core\Exceptions\PackageException;
use Core\Managers\ModuleManager;
use Core\Support\Result;

final class ModuleInstaller
{
    public function __construct(
        private ModuleManager $manager,
        private RegistryInterface $registry,
        private HookInterface $hooks,
    ) {}

    public function install(string $name, array $config = []): ResultInterface
    {
        $result = $this->manager->register($name, $config);

        if (!$result->ok() || !($result->data() instanceof \Core\Contracts\ManifestInterface)) {
            return $result;
        }

        $manifest = $result->data();
        $slug = $manifest->getSlug();

        $this->manager->load($slug);

        InstalledPackage::updateOrCreate(
            ['type' => 'module', 'slug' => $slug],
            [
                'name' => $manifest->get('name', $slug),
                'version' => $manifest->get('version', '1.0.0'),
                'status' => 'active',
            ]
        );

        $this->hooks->doAction('package.installed', $slug, 'module');

        return Result::ok('Módulo instalado.', ['slug' => $slug]);
    }

    public function uninstall(string $slug): ResultInterface
    {
        $this->hooks->doAction('package.uninstalling', $slug, 'module');

        $this->manager->reload($slug);

        InstalledPackage::where('type', 'module')->where('slug', $slug)->delete();

        $this->hooks->doAction('package.uninstalled', $slug, 'module');

        return Result::ok('Módulo desinstalado.', ['slug' => $slug]);
    }
}
