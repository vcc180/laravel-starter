@extends('layouts.admin')

@section('title', 'Perfil')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">{{ $item->name }}</h1>
        <div class="btn-group">
            <a href="{{ route('admin.roles.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted mb-1">Slug</p>
        <p>{{ $item->slug }}</p>

        <p class="text-muted mb-1 mt-3">Descrição</p>
        <p>{{ $item->description ?: '—' }}</p>

        <p class="text-muted mb-1 mt-3">Permissões</p>
        <div class="d-flex flex-wrap gap-2">
            @forelse($item->permissions as $permission)
                <span class="badge bg-light text-dark border">{{ $permission->name }} <code class="ms-1">{{ $permission->slug }}</code></span>
            @empty
                <span class="text-muted">Sem permissões.</span>
            @endforelse
        </div>
    </div>
</div>
@endsection
