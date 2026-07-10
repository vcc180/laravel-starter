<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead><tr><th>#</th><th>Nome</th><th>Slug</th><th class="text-end">Ações</th></tr></thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.tags.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form class="d-inline" action="{{ route('admin.tags.destroy', $item) }}" method="post" onsubmit="return confirm('Excluir?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Nenhuma tag.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
