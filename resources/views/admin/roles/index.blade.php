@extends('layouts.admin')

@section('title', 'Perfis')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Perfis</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">Novo perfil</a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-3" method="get" action="{{ route('admin.roles.index') }}">
            <div class="col-sm-8">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Buscar perfil">
            </div>
            <div class="col-sm-4">
                <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead><tr><th>#</th><th>Nome</th><th>Slug</th><th class="text-end">Ações</th></tr></thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="badge bg-light text-dark">{{ $item->slug }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.roles.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form class="d-inline" action="{{ route('admin.roles.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Nenhum perfil.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection
