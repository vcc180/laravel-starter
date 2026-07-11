@extends('layouts.admin')

@section('title', 'Novo usuário')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Novo usuário</h1></div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required minlength="6">
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Perfil</label>
                <select name="roles[]" class="form-select" multiple>
                    <option value="">Sem perfil</option>
                    @foreach(\App\Models\Role::orderBy('name')->get() as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="form-text">Segure Ctrl/Cmd para selecionar mais de um.</div>
                @error('roles') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
