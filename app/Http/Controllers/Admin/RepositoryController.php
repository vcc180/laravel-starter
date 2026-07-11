<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Core\Installers\PackageInstaller;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {
        $manifestPath = base_path('repository-manifest.json');
        $manifest = \Illuminate\Support\Facades\File::exists($manifestPath)
            ? json_decode(\Illuminate\Support\Facades\File::get($manifestPath), true)
            : ['packages' => []];

        $packages = $manifest['packages'] ?? [];

        $installed = \App\Models\InstalledPackage::all()
            ->keyBy(fn ($item) => $item->type . '/' . $item->slug);

        $types = ['module' => 'modules', 'plugin' => 'plugins', 'theme' => 'themes'];

        return view('admin.repository.index', [
            'packages' => $packages,
            'installed' => $installed,
            'types' => $types,
        ]);
    }

    public function install(Request $request, string $type, string $slug)
    {
        $installer = app(\Core\Installers\PackageInstaller::class);
        $result = $installer->install($type, $slug);

        if (!$result->isOk()) {
            return back()->with('error', $result->message() ?: 'Não foi possível instalar este pacote.');
        }

        $defaultMessage = match ($type) {
            'plugin' => 'Plugin instalado com sucesso.',
            'theme' => 'Tema instalado com sucesso.',
            default => 'Pacote instalado com sucesso.',
        };

        return back()->with('success', $defaultMessage);
    }

    public function uninstall(Request $request, string $type, string $slug)
    {
        $installer = app(\Core\Installers\PackageInstaller::class);
        $result = $installer->types()[$type]?->uninstall($slug);

        if (!$result->isOk()) {
            return back()->with('error', $result->message());
        }

        return back()->with('success', $result->message());
    }
}
