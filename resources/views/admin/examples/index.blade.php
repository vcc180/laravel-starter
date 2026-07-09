@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Exemplos</h1>
        <a class="btn btn-sm btn-primary" href="{{ route('admin.examples.create') }}">Novo</a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-3" method="get" action="{{ route('admin.examples.index') }}">
            <div class="col-sm-8">
                <input class="form-control" name="q" value="{{ $term }}" placeholder="Buscar">
            </div>
            <div class="col-sm-4">
                <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead><tr><th>#</th><th>Nome</th><th></th></tr></thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.examples.show', $item) }}">Ver</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.examples.edit', $item) }}">Editar</a>
                                <form class="d-inline" method="post" action="{{ route('admin.examples.destroy', $item) }}" onsubmit="return confirm('Remover?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Remover</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Nenhum registro.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection
