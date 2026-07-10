<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstalledPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RepositoryController extends Controller
{
    public function index()
    {
        $manifestPath = base_path('repository-manifest.json');
        $manifest = File::exists($manifestPath) ? json_decode(File::get($manifestPath), true) : ['packages' => []];
        $packages = $manifest['packages'] ?? [];

        $installed = InstalledPackage::all()->keyBy(fn($i) => $i->type.'/'.$i->slug);

        $types = ['module' => 'modules', 'plugin' => 'plugins', 'theme' => 'themes'];

        return view('admin.repository.index', [
            'packages' => $packages,
            'installed' => $installed,
            'types' => $types,
        ]);
    }

    public function install(Request $request, string $type, string $slug)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:module,plugin,theme',
            'slug' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $type = $validated['type'];
        $slug = $validated['slug'];

        $root = base_path($this->typePath($type));
        $packagePath = $root.'/'.$slug;

        if (!File::exists($packagePath)) {
            return back()->with('error', "Pacote não encontrado em {$packagePath}");
        }

        $moduleJsonPath = $packagePath.'/module.json';
        if (!File::exists($moduleJsonPath)) {
            return back()->with('error', 'Arquivo module.json ausente no pacote');
        }

        $module = json_decode(File::get($moduleJsonPath), true);

        InstalledPackage::updateOrCreate(
            ['type' => $type, 'slug' => $slug],
            [
                'name' => $module['name'] ?? $slug,
                'version' => $module['version'] ?? '1.0.0',
                'status' => 'active',
            ]
        );

        if (!empty($module['provides']) && is_array($module['provides'])) {
            if (in_array('migrations', $module['provides'], true)) {
                try {
                    Artisan::call('migrate', ['--path' => "modules/{$slug}/database/migrations", '--force' => true]);
                } catch (\Throwable $e) {
                    // migrations do módulo podem falhar se já executadas; não bloquear instalação
                }
            }
        }

        $provider = $packagePath.'/src/Providers/'.($module['provider'] ?? ucfirst($slug).'ServiceProvider.php');
        if (File::exists($provider) && class_exists($ns = $this->guessProviderClass($slug))) {
            // ServiceProvider discovery é feito pelo package:discover; manter instalação não destrutiva
        }

        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return back()->with('success', 'Pacote instalado com sucesso.');
    }

    public function uninstall(Request $request, string $type, string $slug)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:module,plugin,theme',
            'slug' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $type = $validated['type'];
        $slug = $validated['slug'];

        $package = InstalledPackage::where('type', $type)->where('slug', $slug)->first();

        if (!$package) {
            return back()->with('error', 'Pacote não está instalado.');
        }

        InstalledPackage::where('type', $type)->where('slug', $slug)->delete();

        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return back()->with('success', 'Pacote desinstalado. Arquivos mantidos para reinstalação.');
    }

    private function typePath(string $type): string
    {
        return match ($type) {
            'plugin' => 'plugins',
            'theme' => 'themes',
            default => 'modules',
        };
    }

    private function guessProviderClass(string $slug): string
    {
        $studly = str_replace(['-', '_'], '', ucwords($slug, '_-'));
        return 'Modules\\'.ucfirst($slug).'\\Providers\\'.ucfirst($studly).'ServiceProvider';
    }
}
