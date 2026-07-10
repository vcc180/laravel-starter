@extends('layouts.admin')

@section('title', 'Articles')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-sm btn-primary">Novo</a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-3" method="get" action="{{ route('admin.articles.index') }}">
            <div class="col-sm-8">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Buscar título/conteúdo">
            </div>
            <div class="col-sm-4">
                <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Ativo</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->is_active ? 'Sim' : 'Não' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.articles.show', $item) }}" class="btn btn-sm btn-secondary">Ver</a>
                                <a href="{{ route('admin.articles.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form class="d-inline" action="{{ route('admin.articles.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Nenhum registro.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection
