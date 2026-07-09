@extends('layouts.admin')

@section('title', 'Articles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h5 mb-0">Articles</h1>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-sm btn-primary">Novo</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Ativo</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->is_active ? 'Sim' : 'Não' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.articles.show', $item) }}" class="btn btn-sm btn-secondary">Ver</a>
                        <a href="{{ route('admin.articles.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form class="d-inline" action="{{ route('admin.articles.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4">Nenhum registro.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $articles->links('vendor.pagination.bootstrap-5') }}
@endsection
