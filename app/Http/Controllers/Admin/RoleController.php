<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $items = Role::query()
            ->orderByDesc('id')
            ->paginate(20);

        $term = (string) $request->query('q', '');

        if ($term !== '') {
            $items = Role::query()
                ->where('name', 'like', "%{$term}%")
                ->orWhere('slug', 'like', "%{$term}%")
                ->orderByDesc('id')
                ->paginate(20)
                ->withQueryString();
        }

        return view('admin.roles.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        return view('admin.roles.create', ['permissions' => $permissions]);
    }

    public function store(RoleRequest $request)
    {
        $data = $request->validated();

        $role = Role::create($data);
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('status', 'Perfil criado.');
    }

    public function show(Role $role)
    {
        return view('admin.roles.show', ['item' => $role]);
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        return view('admin.roles.edit', [
            'item' => $role,
            'permissions' => $permissions,
            'selected' => $role->permissions()->pluck('permissions.id')->all(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:roles,slug,' . $role->id],
            'description' => ['nullable', 'string'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update($data);
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('status', 'Perfil atualizado.');
    }

    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return back()->with('status', 'Removido.');
    }
}
