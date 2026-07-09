@extends('layouts.admin')

@section('title', 'Permissões')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h5 mb-0">Permissões</h1>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-sm btn-primary">Nova</a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($items->count())
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Rótulo</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->label }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.permissions.show', $item) }}" class="btn btn-sm btn-secondary">Ver</a>
                                <a href="{{ route('admin.permissions.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form class="d-inline" action="{{ route('admin.permissions.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-4">Nenhuma permissão.</div>
        @endif
    </div>
</div>

{{ $items->links('vendor.pagination.bootstrap-5') }}

<form class="mt-3" method="get" action="{{ route('admin.permissions.index') }}">
    <div class="input-group">
        <input type="text" name="q" value="{{ $term }}" class="form-control" placeholder="Buscar...">
        <button class="btn btn-secondary" type="submit">Buscar</button>
    </div>
</form>
@endsection
