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

    public function install(Request $request, string $type, string $slug, PackageInstaller $installer)
    {
        $validated = $request->validate([
            'type' => 'required|in:module,plugin,theme',
            'slug' => 'required|string|max:255',
        ]);

        $type = $validated['type'];
        $slug = $validated['slug'];

        $result = $installer->install($type, $slug);

        if (!$result->isOk()) {
            return back()->with('error', $result->message());
        }

        return back()->with('success', $result->message());
    }

    public function uninstall(Request $request, string $type, string $slug, PackageInstaller $installer)
    {
        $validated = $request->validate([
            'type' => 'required|in:module,plugin,theme',
            'slug' => 'required|string|max:255',
        ]);

        $type = $validated['type'];
        $slug = $validated['slug'];

        $result = $installer->types()[$type]?->uninstall($slug);

        if (!$result->isOk()) {
            return back()->with('error', $result->message());
        }

        return back()->with('success', $result->message());
    }
}
