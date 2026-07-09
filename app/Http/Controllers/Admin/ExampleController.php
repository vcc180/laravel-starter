<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Example;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function index(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        $query = Example::query();

        if ($term !== '') {
            $query->where('name', 'like', "%{$term}%")
                  ->orWhere('notes', 'like', "%{$term}%");
        }

        $items = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.examples.index', compact('items', 'term'));
    }

    public function create()
    {
        return view('admin.examples.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        Example::create($validated);

        return redirect()->route('admin.examples.index')->with('status', 'Registro criado.');
    }

    public function show(Example $example)
    {
        return view('admin.examples.show', compact('example'));
    }

    public function edit(Example $example)
    {
        return view('admin.examples.edit', compact('example'));
    }

    public function update(Request $request, Example $example)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $example->update($validated);

        return redirect()->route('admin.examples.index')->with('status', 'Registro atualizado.');
    }

    public function destroy(Example $example)
    {
        $example->delete();

        return redirect()->route('admin.examples.index')->with('status', 'Removido.');
    }
}
