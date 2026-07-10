@extends('layouts.admin')

@section('title', 'Novo perfil')

@section('content')
<div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Novo perfil</h1></div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input class="form-control" name="slug" value="{{ old('slug') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Permissões</label>
                @foreach($permissions as $module => $items)
                    <div class="fw-semibold mt-3 mb-2 text-uppercase small text-muted">{{ $module ?? 'Geral' }}</div>
                    <div class="row">
                        @foreach($items as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="p{{ $permission->id }}" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="p{{ $permission->id }}">
                                        {{ $permission->name }}
                                        <div class="small text-muted">{{ $permission->slug }}</div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <button class="btn btn-dark" type="submit">Salvar</button>
            <a class="btn btn-link" href="{{ route('admin.roles.index') }}">Voltar</a>
        </form>
    </div>
</div>
@endsection
