@extends('layouts.admin')

@section('title', 'Editar usuário')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Editar usuário</h1></div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="post">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" minlength="6" placeholder="Deixe em branco para não alterar">
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="6">
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
