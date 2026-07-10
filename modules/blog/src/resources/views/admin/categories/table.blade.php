<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead><tr><th>#</th><th>Nome</th><th class="text-end">Ações</th></tr></thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form class="d-inline" action="{{ route('admin.categories.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center py-4 text-muted">Nenhuma categoria.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
