@extends('blog::admin.layout')

@section('title', 'Editar categoria')

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="h5 mb-0">Editar categoria</h1>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.categories.update', $item) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input class="form-control" name="name" value="{{ old('name', $item->name) }}">
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a class="btn btn-link" href="{{ route('admin.categories.index') }}">Voltar</a>
        </form>
    </div>
</div>
@endsection
