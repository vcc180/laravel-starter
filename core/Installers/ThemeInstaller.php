<?php

namespace Core\Installers;

use App\Models\InstalledPackage;
use Core\Contracts\HookInterface;
use Core\Contracts\RegistryInterface;
use Core\Contracts\ResultInterface;
use Core\Managers\ThemeManager;
use Core\Support\Result;

final class ThemeInstaller
{
    public function __construct(
        private ThemeManager $manager,
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
            ['type' => 'theme', 'slug' => $slug],
            [
                'name' => $manifest->get('name', $slug),
                'version' => $manifest->get('version', '1.0.0'),
                'status' => 'active',
            ]
        );

        $this->hooks->doAction('package.installed', $slug, 'theme');

        return Result::ok('Tema instalado.', ['slug' => $slug]);
    }

    public function uninstall(string $slug): ResultInterface
    {
        $this->hooks->doAction('package.uninstalling', $slug, 'theme');

        $this->manager->reload($slug);

        InstalledPackage::where('type', 'theme')->where('slug', $slug)->delete();

        $this->hooks->doAction('package.uninstalled', $slug, 'theme');

        return Result::ok('Tema desinstalado.', ['slug' => $slug]);
    }
}
