@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Idiomas</h1>
        <a class="btn btn-sm btn-primary" href="{{ route('admin.locales.create') }}">Novo</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead><tr><th>#</th><th>Código</th><th>Nome</th><th></th></tr></thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="{{ !$item->is_active ? 'table-secondary' : '' }}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->locale }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.locales.edit', $item) }}">Editar</a>
                                <form class="d-inline" method="post" action="{{ route('admin.locales.destroy', $item) }}" onsubmit="return confirm('Remover?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Remover</button>
                                </form>
                                @if(!$item->is_active)
                                    <form class="d-inline" method="post" action="{{ route('admin.locales.set-default', $item) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" type="submit">Tornar padrão</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Nenhum registro.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection
