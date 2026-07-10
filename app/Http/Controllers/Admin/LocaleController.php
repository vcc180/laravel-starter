<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\LocaleRequest;
use App\Models\Locale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocaleController extends Controller
{
    public function index(Request $request)
    {
        $term = (string) $request->query('q', '');

        $query = Locale::query();

        if ($term !== '') {
            $query->where('locale', 'like', "%{$term}%")
                  ->orWhere('name', 'like', "%{$term}%");
        }

        $items = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.locales.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function create()
    {
        return view('admin.locales.create');
    }

    public function store(LocaleRequest $request)
    {
        Locale::create($request->validated());

        return redirect()->route('admin.locales.index')->with('status', 'Idioma criado.');
    }

    public function show(Locale $locale)
    {
        return view('admin.locales.show', compact('locale'));
    }

    public function edit(Locale $locale)
    {
        return view('admin.locales.edit', compact('locale'));
    }

    public function update(LocaleRequest $request, Locale $locale)
    {
        $locale->update($request->validated());

        return redirect()->route('admin.locales.index')->with('status', 'Idioma atualizado.');
    }

    public function destroy(Locale $locale)
    {
        $locale->delete();

        return redirect()->route('admin.locales.index')->with('status', 'Idioma removido.');
    }

    public function setDefault(Request $request, Locale $locale)
    {
        Cache::rememberForever('locale.default', static fn() => 'en');
        Cache::forever('locale.default', $locale->locale);

        return back()->with('success', 'Idioma padrão atualizado para ' . $locale->name . '.');
    }
}
