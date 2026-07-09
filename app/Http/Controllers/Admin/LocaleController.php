<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Models\Locale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocaleController extends \App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        $items = Locale::orderByDesc('id')->paginate(50);

        return view('admin.locales.index', [
            'items' => $items,
        ]);
    }

    public function setDefault(Request $request, Locale $locale)
    {
        Cache::rememberForever('locale.default', static function () {
            return 'en';
        });
        Cache::forever('locale.default', $locale->locale);

        return back()->with('success', 'Idioma padrão atualizado para ' . $locale->name . '.');
    }
}
