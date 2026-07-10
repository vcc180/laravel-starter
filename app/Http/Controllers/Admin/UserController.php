<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', ['users' => User::query()->orderByDesc('id')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ];

        User::create($data);

        return redirect()->route('admin.users.index')->with('status', 'Usuário criado.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
        ];

        $password = $request->validated('password');

        if ($password !== null && $password !== '') {
            $data['password'] = Hash::make($password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Usuário atualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Usuário removido.');
    }
}
