<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\ExampleRequest;
use App\Models\Example;

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

    public function store(ExampleRequest $request)
    {
        Example::create($request->validated());

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

    public function update(ExampleRequest $request, Example $example)
    {
        $example->update($request->validated());

        return redirect()->route('admin.examples.index')->with('status', 'Registro atualizado.');
    }

    public function destroy(Example $example)
    {
        $example->delete();

        return redirect()->route('admin.examples.index')->with('status', 'Removido.');
    }
}
