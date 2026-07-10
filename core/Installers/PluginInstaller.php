<?php

namespace Core\Installers;

use App\Models\InstalledPackage;
use Core\Contracts\HookInterface;
use Core\Contracts\RegistryInterface;
use Core\Contracts\ResultInterface;
use Core\Exceptions\PackageException;
use Core\Managers\PluginManager;
use Core\Support\Result;

final class PluginInstaller
{
    public function __construct(
        private PluginManager $manager,
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
            ['type' => 'plugin', 'slug' => $slug],
            [
                'name' => $manifest->get('name', $slug),
                'version' => $manifest->get('version', '1.0.0'),
                'status' => 'active',
            ]
        );

        $this->hooks->doAction('package.installed', $slug, 'plugin');

        return Result::ok('Plugin instalado.', ['slug' => $slug]);
    }

    public function uninstall(string $slug): ResultInterface
    {
        $this->hooks->doAction('package.uninstalling', $slug, 'plugin');

        $this->manager->reload($slug);

        InstalledPackage::where('type', 'plugin')->where('slug', $slug)->delete();

        $this->hooks->doAction('package.uninstalled', $slug, 'plugin');

        return Result::ok('Plugin desinstalado.', ['slug' => $slug]);
    }
}
