<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::query()->orderByDesc('id')->paginate(20);

        return view('admin.users.index', ['users' => $users]);
    }

    public function edit(User $user)
    {
        $roles = \App\Models\Role::orderBy('name')->get();
        $selected = $user->roles()->pluck('id')->all();

        return view('admin.users.roles', [
            'user' => $user,
            'roles' => $roles,
            'selected' => $selected,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index')->with('status', 'Perfis do usuário atualizados.');
    }
}
