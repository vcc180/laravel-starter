@extends('layouts.admin')

@section('title', 'Blog posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h5 mb-0">Posts</h1>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-sm btn-primary">Novo post</a>
</div>

<form class="row g-2 mb-3" method="get" action="{{ route('admin.blog.index') }}">
    <div class="col-sm-8">
        <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Buscar título">
    </div>
    <div class="col-sm-4">
        <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead><tr><th>#</th><th>Título</th><th>Categoria</th><th>Publicado</th><th class="text-end">Ações</th></tr></thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->is_published ? 'Sim' : 'Não' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.blog.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form class="d-inline" action="{{ route('admin.blog.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Nenhum post.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
