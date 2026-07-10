@extends('blog::admin.layout')

@section('title', 'Nova tag')

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="h5 mb-0">Nova tag</h1>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.tags.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input class="form-control" name="name" value="{{ old('name') }}">
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input class="form-control" name="slug" value="{{ old('slug') }}">
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary" type="submit">Criar</button>
            <a class="btn btn-link" href="{{ route('admin.tags.index') }}">Voltar</a>
        </form>
    </div>
</div>
@endsection
