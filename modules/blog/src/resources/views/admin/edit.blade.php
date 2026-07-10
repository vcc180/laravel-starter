@extends('layouts.admin')

@section('title', 'Editar post')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Editar post</h1>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.blog.update', $item) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input class="form-control" name="title" value="{{ old('title', $item->title) }}">
                @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input class="form-control" name="slug" value="{{ old('slug', $item->slug) }}">
                @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="blog_category_id" class="form-select">
                    <option value="">Sem categoria</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" @selected(old('blog_category_id', $item->blog_category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('blog_category_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Conteúdo</label>
                <textarea class="form-control" name="body" rows="6">{{ old('body', $item->body) }}</textarea>
                @error('body')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $item->is_published))>
                <label class="form-check-label" for="is_published">Publicado</label>
            </div>
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a class="btn btn-link" href="{{ route('admin.blog.index') }}">Voltar</a>
        </form>
    </div>
</div>
@endsection
