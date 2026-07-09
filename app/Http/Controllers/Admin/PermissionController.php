<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $term = (string) $request->query('q', '');

        $query = Permission::query();

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('label', 'like', "%{$term}%");
            });
        }

        $items = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.permissions.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        Permission::create($data);

        return redirect()->route('admin.permissions.index')->with('status', 'Permissão criada.');
    }

    public function show(Permission $permission)
    {
        return view('admin.permissions.show', ['item' => $permission]);
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', ['item' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->id],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $permission->update($data);

        return redirect()->route('admin.permissions.index')->with('status', 'Permissão atualizada.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('status', 'Removido.');
    }
}
