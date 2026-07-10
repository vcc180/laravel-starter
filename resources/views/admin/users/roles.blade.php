@extends('layouts.admin')

@section('title', 'Perfis do usuário')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Perfis: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
    </div>
    <div class="card-body">
        <p class="text-muted">{{ $user->email }}</p>

        <form method="post" action="{{ route('admin.users.roles.update', $user) }}" class="mt-3">
            @csrf @method('PUT')
            <div class="row">
                @foreach($roles as $role)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="r{{ $role->id }}" value="{{ $role->id }}" {{ in_array($role->id, $selected) ? 'checked' : '' }}>
                            <label class="form-check-label" for="r{{ $role->id }}">
                                {{ $role->name }}
                                <div class="small text-muted">{{ $role->slug }}</div>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="btn btn-dark mt-3" type="submit">Salvar perfis</button>
        </form>
    </div>
</div>
@endsection
